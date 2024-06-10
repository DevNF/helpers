<?php
use PHPUnit\Framework\TestCase;

final class Boleto extends TestCase
{
    protected function setUp(): void
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function testBoletoComNumeracaoInvalida()
    {
        $this->assertEquals([
            'status' => false,
            'msg' => 'Tipo de boleto inválido'
        ], readDigitableLine('12345678901234567890123456789012345678901234567890123456789012345678901234567890'));
    }

    public function testBoletoCodigoDeBarrasBancoDe5Campos()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 1,
                'category' => 'BANCO',
                'barcode' => '10499898100000214032006561000100040099726390',
                'value' => 214.03,
                'due_date' => '2022-05-10',
                'bank_code' => '104',
            ]
        ], readDigitableLine('10499898100000214032006561000100040099726390'));
    }

    public function testBoletoLinhaDigitavelBancoDe5Campos()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 1,
                'category' => 'BANCO',
                'barcode' => '10499898100000214032006561000100040099726390',
                'value' => 214.03,
                'due_date' => '2022-05-10',
                'bank_code' => '104',
            ]
        ], readDigitableLine('10492006506100010004200997263900989810000021403'));
    }

    public function testBoletoCartaoDeCreditoCodigoDeBarras()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 1,
                'category' => 'CARTAO_DE_CREDITO',
                'barcode' => '23797000000000000004150090019801673500021140',
                'value' => 0.0,
                'due_date' => '',
                'bank_code' => '237',
            ]
        ], readDigitableLine('23797000000000000004150090019801673500021140'));
    }

    public function testBoletoCartaoDeCreditoLinhaDigitavel()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 1,
                'category' => 'CARTAO_DE_CREDITO',
                'barcode' => '23797000000000000004150090019801673500021140',
                'value' => 0.0,
                'due_date' => '',
                'bank_code' => '237',
            ]
        ], readDigitableLine('23794150099001980167035000211405700000000000000'));
    }

    public function testBoletoConcessionariaEnergiaCodigoDeBarras()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'CONVENIO_ENERGIA_ELETRICA_E_GAS',
                'barcode' => '83860000005096000190000008017823000034306271',
                'value' => 509.6,
                'due_date' => '1997-10-11',
                'bank_code' => '838',
            ]
        ], readDigitableLine('83860000005096000190000008017823000034306271'));
    }

    public function testBoletoConcessionariaEnergiaLinhaDigitavel()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'CONVENIO_ENERGIA_ELETRICA_E_GAS',
                'barcode' => '83860000005096000190000008017823000034306271',
                'value' => 509.6,
                'due_date' => '1997-10-11',
                'bank_code' => '838',
            ]
        ], readDigitableLine('838600000050096000190009000801782309000343062712'));
    }

    public function testBoletoConcessionariaGovernoLinhaDigitavel() {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'ARRECADACAO_ORGAOS_GOVERNAMENTAIS',
                'barcode' => '85890000464524601791606075930508683148300001',
                'value' => 46452.46,
                'due_date' => '2020-07-12',
                'bank_code' => '858',
            ]
        ], readDigitableLine('858900004641524601791605607593050865831483000010'));
    }

    public function testBoletoConcessionariaGovernoCodigoDeBarras() {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'ARRECADACAO_ORGAOS_GOVERNAMENTAIS',
                'barcode' => '85890000464524601791606075930508683148300001',
                'value' => 46452.46,
                'due_date' => '2020-07-12',
                'bank_code' => '858',
            ]
        ], readDigitableLine('85890000464524601791606075930508683148300001'));
    }

    public function testConversaoSemFormatacaoCodigoDeBarrasParaLinhaDigitavel()
    {
        $this->assertEquals('858200000007572503282030560708202107539591904460', formatBarcodeToDigitableLine('85820000000572503282035607082021053959190446'));
    }
}