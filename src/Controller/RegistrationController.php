<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_register', methods: ['GET'])]
    public function register(): Response
    {
        // Renderiza el formulario de registro
        return $this->render('registration/register.html.twig', [
            'currentYear' => (int) (new \DateTime())->format('Y'), // Pasamos el año actual como entero
        ]);
    }
    #[Route('/register/process', name: 'process_registration', methods: ['POST'])]
    public function processRegistration(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        // Captura los datos del formulario
        $firstName = $request->request->get('nombre');
        $lastName = $request->request->get('apellido');
        $birthDate = $request->request->get('anio') . '-' . $request->request->get('mes') . '-' . $request->request->get('dia');
        $gender = $request->request->get('genero');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $phone = $request->request->get('telefono');
        $dni = $request->request->get('dni');
        $street = $request->request->get('calle');
        $streetNumber = $request->request->get('numero');
        $locationDetails = $request->request->get('detalles');

        // Procesar las fotos del DNI (frente y dorso)
        $dniFrontFile = $request->files->get('dni_front');
        $dniBackFile = $request->files->get('dni_back');

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setBirthDate(new \DateTime($birthDate));
        $user->setGender($gender);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setDni($dni);
        $user->setStreet($street);
        $user->setStreetNumber($streetNumber);
        $user->setLocationDetails($locationDetails);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Encripta la contraseña
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setIsActive(true);

        // Guardar las fotos del DNI
        if ($dniFrontFile) {
            try {
                $dniFrontFileName = $this->uploadFile($dniFrontFile, $slugger);
                $user->setDniFrontPhoto($dniFrontFileName);
            } catch (FileException $e) {
                $this->addFlash('error', 'Error al subir la foto del DNI (frente).');
                return $this->redirectToRoute('user_register');
            }
        }

        if ($dniBackFile) {
            try {
                $dniBackFileName = $this->uploadFile($dniBackFile, $slugger);
                $user->setDniBackPhoto($dniBackFileName);
            } catch (FileException $e) {
                $this->addFlash('error', 'Error al subir la foto del DNI (dorso).');
                return $this->redirectToRoute('user_register');
            }
        }

        // Guardar el usuario en la base de datos
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirigir a la página de éxito
        return $this->redirectToRoute('registration_success');
    }

    #[Route('/register/success', name: 'registration_success', methods: ['GET'])]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }

    private function uploadFile($file, SluggerInterface $slugger): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getParameter('uploads_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            throw new FileException('No se pudo guardar el archivo.');
        }

        return $newFilename;
    }
}
