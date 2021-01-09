<?php

namespace App\Controller;

use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;



class SecurityController extends AbstractController
{

    private $passwordEncoder;
    private $emailVerifier;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EmailVerifier $emailVerifier)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->emailVerifier = $emailVerifier;
    }



    /**
     * Login
     *
     * @Route("/security/login", name="security_login")a
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
         if ( $this->getUser() ) {
             return $this->redirectToRoute('/');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }




    /**
     * Logout
     *
     * @Route("/security/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }




    /**
     * Registration
     *
     * @Route("/security/register", name="security_register")
     */
    public function register(Request $request, ValidatorInterface $validator, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        //dd($this->getUser());
        /* user is auth */
        if ($this->getUser()) {
            return $this->redirectToRoute('target_path');
        }
        /* show form */
        if ($request->isMethod('GET')) {
            return $this->render('security/register.html.twig');
        }
        /* if POST - check csrf */
        if ( !$this->isCsrfTokenValid('registration', $request->request->get('_csrf_token')) ) {
            $this->addFlash('notice', "Some message");
            return $this->render('security/register.html.twig');
        }

        try {
            /* fill user */
            $user = new User();
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));
            $user->setNativeLang( User::possibleLanguages()[rand(0,1)] );
            $user->setRoles( $user->getRoles() );
            $password = $this->passwordEncoder->encodePassword($user, $request->request->get('password'));
            $user->setPassword($password);
            /* validate */
            if ( count($errors = $validator->validate($user)) > 0 ) {
                throw new \ErrorException((string)$errors);
            }
            /* save user */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            /* login */
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        } catch (\ErrorException $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->render('security/register.html.twig');
        }

        return $this->render('/');
    }




    /**
     * Verify email
     *
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail( Request $request ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }





}
