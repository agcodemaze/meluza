<?php

namespace Tests\Controller\Pages;

use PHPUnit\Framework\TestCase;
use App\Controller\Pages\Home;

class HomeTest extends TestCase
{
    /**
     * @test
     * Verifica se o método 'getHome' existe na classe Home.
     */

    public function oMetodoGetHomeExisteNaClasseHome()
    {
        $home = new Home();
        $this->assertTrue(method_exists($home, 'getHome'));
    }

    /**
     * @test
     * Verifica se o nome esta correto.
     */

    public function oMetodoGetHomeRetornaHtmlComONomeDaOrganizacao()
    {
        $home = new Home();        
        // 2. Act (Ação)
        $htmlContent = $home->getHome();
        // Vamos verificar se a string retornada contém o nome da organização
        $this->assertStringContainsString('Site do mics', $htmlContent);

    }
}