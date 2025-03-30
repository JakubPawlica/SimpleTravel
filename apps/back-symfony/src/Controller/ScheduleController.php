<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ScheduleRepository;
use Symfony\Component\Serializer\SerializerInterface;

class ScheduleController extends AbstractController
{
    #[Route('/api/schedule', name: 'api_schedule_list', methods: ['GET'])]
    public function index(ScheduleRepository $scheduleRepository, SerializerInterface $serializer): JsonResponse
    {
        $schedules = $scheduleRepository->findAll();
        $json = $serializer->serialize($schedules, 'json', ['groups' => ['schedule:read']]);

        return JsonResponse::fromJsonString($json);
    }
}
