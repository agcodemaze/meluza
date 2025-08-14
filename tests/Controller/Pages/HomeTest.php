<?php

namespace Tests\Controller\Pages;

use PHPUnit\Framework\TestCase;
use App\Controller\Pages\Home;

class HomeTest extends TestCase
{
    /**
     * @test
     * Para executar o teste no terminal:
     * bash -> ./bin/phpunit
     */

    /**
     * @test
     * Verifica se o método 'getHome' existe na classe Home.
     */
    public function oMetodoGetHomeExisteNaClasseHome()
    {
        // ... Lógica do seu primeiro teste ...
        $home = new Home();
        $this->assertTrue(method_exists($home, 'getHome'));
    }

    /**
     * @test
     * Verifica se o nome esta correto.
     */

    public function oMetodoGetHomeRetornaHtmlComONomeDaOrganizacao()
    {
        // 1. Arrange (Preparação)
        // Você precisa de uma forma de mockar (simular) a classe Organization
        // e o seu método getPage para que o teste seja focado apenas em 'getHome'
        
        // Neste exemplo, vamos apenas verificar a string final
        $home = new Home();
        
        // 2. Act (Ação)
        $htmlContent = $home->getHome();
        
        // 3. Assert (Verificação)
        // Vamos verificar se a string retornada contém o nome da organização
        $this->assertStringContainsString('Site do mics', $htmlContent);
    }
}