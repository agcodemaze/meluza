<?php

// O namespace do seu teste deve refletir a estrutura da pasta 'tests'
namespace Tests\Controller\Pages;

use PHPUnit\Framework\TestCase;
use App\Controller\Pages\Home;

class HomeTest extends TestCase
{
    /**
     * @test
     */
    public function oMetodoGetHomeExisteNaClasseHome()
    {
        // 1. Arrange (Preparação)
        // Cria uma instância da classe que você quer testar
        $home = new Home();

        // 2. Act (Ação)
        // Não precisamos de uma ação específica para este teste, pois
        // vamos verificar apenas a existência do método.

        // 3. Assert (Verificação)
        // O PHPUnit tem várias "asserts" (afirmações)
        // Aqui, verificamos se o método 'getHome' existe na classe $home
        $this->assertTrue(method_exists($home, 'getHome'), 'O método getHome() não existe na classe Home.');
    }
}