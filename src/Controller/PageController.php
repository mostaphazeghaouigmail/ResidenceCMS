<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Page;
use App\Form\Type\ContactType;
use App\Message\SendFeedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends BaseController
{
    /**
     * @Route("/info/{slug}", methods={"GET|POST"}, name="page")
     */
    public function pageShow(Request $request, Page $page, MessageBusInterface $messageBus): Response
    {
        if ($page->getAddContactForm() && '' !== $page->getContactEmailAddress()) {
            $contact = new Contact();
            $contact->setToEmail($page->getContactEmailAddress());

            $form = $this->createForm(ContactType::class, $contact);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $messageBus->dispatch(new SendFeedback($contact));
                $this->addFlash('success', 'message.was_sent');

                return $this->redirectToRoute('page', ['slug' => $page->getSlug()]);
            }
        }

        return $this->render('page/show.html.twig',
            [
                'site' => $this->site(),
                'page' => $page,
                'form' => (!empty($form) ? $form->createView() : []),
            ]
        );
    }
}
