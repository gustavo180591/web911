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
        // Renderiza el formulario de registro y pasa el año actual
        return $this->render('registration/register.html.twig', [
            'currentYear' => (int)(new \DateTime())->format('Y'),
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
        $dia = $request->request->get('dia');
        $mes = $request->request->get('mes');
        $anio = $request->request->get('anio');
        $gender = $request->request->get('genero');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $phone = $request->request->get('telefono');
        $dni = $request->request->get('dni');
        $street = $request->request->get('street', '');
        $latitude = $request->request->get('latitude', '');
        $longitude = $request->request->get('longitude', '');

        // Validar y construir la fecha de nacimiento
        if (!checkdate((int)$mes, (int)$dia, (int)$anio)) {
            $this->addFlash('error', 'La fecha de nacimiento no es válida.');
            return $this->redirectToRoute('user_register');
        }
        $birthDate = new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $anio, $mes, $dia));

        // Validar que la dirección no esté vacía
        if (empty($street)) {
            $this->addFlash('error', 'La dirección es obligatoria.');
            return $this->redirectToRoute('user_register');
        }

        // Procesar las fotos del DNI (frente y dorso)
        $dniFrontFile = $request->files->get('dni_front');
        $dniBackFile = $request->files->get('dni_back');

        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setBirthDate($birthDate);
        $user->setGender($gender);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Encripta la contraseña
        $user->setPhone($phone);
        $user->setDni($dni);
        $user->setStreet($street);
        $user->setLatitude($latitude ? (float)$latitude : null);
        $user->setLongitude($longitude ? (float)$longitude : null);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setIsActive(true);

        // Subir las fotos del DNI
        try {
            if ($dniFrontFile) {
                $dniFrontFileName = $this->uploadFile($dniFrontFile, $slugger);
                $user->setDniFrontPhoto($dniFrontFileName);
            }
            if ($dniBackFile) {
                $dniBackFileName = $this->uploadFile($dniBackFile, $slugger);
                $user->setDniBackPhoto($dniBackFileName);
            }
        } catch (FileException $e) {
            $this->addFlash('error', 'Error al subir las fotos del DNI: ' . $e->getMessage());
            return $this->redirectToRoute('user_register');
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
            throw new FileException('No se pudo guardar el archivo: ' . $e->getMessage());
        }

        return $newFilename;
    }
}
