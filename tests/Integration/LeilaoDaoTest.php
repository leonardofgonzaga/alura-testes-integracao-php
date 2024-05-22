<?php

namespace Alura\Leilao\Tests\Integration\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    public function testInsercaoEBuscaDevemFuncionar()
    {
        $leilao = new Leilao('Variant 0KM');
        $leilaoDao = new LeilaoDao(ConnectionCreator::getConnection());    

        $leilaoDao->salva($leilao);
        $leiloes = $leilaoDao->recuperarFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variant 0KM', $leiloes[0]->recuperarDescricao());
    }

    
}