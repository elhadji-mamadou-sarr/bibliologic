<?php

namespace App\Test\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LivreControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/livre/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Livre::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livre index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'livre[titre]' => 'Testing',
            'livre[auteur]' => 'Testing',
            'livre[genre]' => 'Testing',
            'livre[langue]' => 'Testing',
            'livre[date_publication]' => 'Testing',
            'livre[nombre_pages]' => 'Testing',
            'livre[localisation]' => 'Testing',
            'livre[etat]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('My Title');
        $fixture->setAuteur('My Title');
        $fixture->setGenre('My Title');
        $fixture->setLangue('My Title');
        $fixture->setDate_publication('My Title');
        $fixture->setNombre_pages('My Title');
        $fixture->setLocalisation('My Title');
        $fixture->setEtat('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livre');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('Value');
        $fixture->setAuteur('Value');
        $fixture->setGenre('Value');
        $fixture->setLangue('Value');
        $fixture->setDate_publication('Value');
        $fixture->setNombre_pages('Value');
        $fixture->setLocalisation('Value');
        $fixture->setEtat('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'livre[titre]' => 'Something New',
            'livre[auteur]' => 'Something New',
            'livre[genre]' => 'Something New',
            'livre[langue]' => 'Something New',
            'livre[date_publication]' => 'Something New',
            'livre[nombre_pages]' => 'Something New',
            'livre[localisation]' => 'Something New',
            'livre[etat]' => 'Something New',
        ]);

        self::assertResponseRedirects('/livre/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getAuteur());
        self::assertSame('Something New', $fixture[0]->getGenre());
        self::assertSame('Something New', $fixture[0]->getLangue());
        self::assertSame('Something New', $fixture[0]->getDate_publication());
        self::assertSame('Something New', $fixture[0]->getNombre_pages());
        self::assertSame('Something New', $fixture[0]->getLocalisation());
        self::assertSame('Something New', $fixture[0]->getEtat());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Livre();
        $fixture->setTitre('Value');
        $fixture->setAuteur('Value');
        $fixture->setGenre('Value');
        $fixture->setLangue('Value');
        $fixture->setDate_publication('Value');
        $fixture->setNombre_pages('Value');
        $fixture->setLocalisation('Value');
        $fixture->setEtat('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/livre/');
        self::assertSame(0, $this->repository->count([]));
    }
}
