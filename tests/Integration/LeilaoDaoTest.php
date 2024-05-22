<?php

namespace Alura\Leilao\Tests\Integration\Service;

use PDO;
use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;

class LeilaoDaoTest extends TestCase
{
    /** @var PDO */
    private static $pdo;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new PDO('sqlite::memory:');
        self::$pdo->exec('CREATE TABLE leiloes (
            id INTEGER PRIMARY KEY, 
            descricao TEXT, 
            finalizado BOOL, 
            dataInicio TEXT
        );');
    }

    protected function setUp(): void
    {
        
        self::$pdo->beginTransaction();
    }

    public function testInsercaoEBuscaDevemFuncionar()
    {
        $leilao = new Leilao('Variant 0KM');
        $pdo = ConnectionCreator::getConnection();
        $leilaoDao = new LeilaoDao($pdo);   

        $leilaoDao->salva($leilao);
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variant 0KM', $leiloes[0]->recuperarDescricao());

        $pdo->exec('DELETE FROM leiloes');
    }

    protected function tearDown(): void
    {
        self::$pdo->rollBack();
    }
}