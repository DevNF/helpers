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
                'barcode' => '34193105600000899001090002312490241396605000',
                'value' => 899.0,
                'due_date' => '2025-04-19',
                'bank_code' => '341',
            ]
        ], readDigitableLine('34193105600000899001090002312490241396605000'));
    }

    public function testBoletoLinhaDigitavelBancoDe5Campos()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 1,
                'category' => 'BANCO',
                'barcode' => '34193105600000899001090002312490241396605000',
                'value' => 899.0,
                'due_date' => '2025-04-19',
                'bank_code' => '341',
            ]
        ], readDigitableLine('34191090080231249024213966050000310560000089900'));
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
                'barcode' => '83640000000206701372025030500479102280030194',
                'value' => 20.67,
                'due_date' => '',
                'bank_code' => '836',
            ]
        ], readDigitableLine('83640000000206701372025030500479102280030194'));
    }

    public function testBoletoConcessionariaEnergiaLinhaDigitavel()
    {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'CONVENIO_ENERGIA_ELETRICA_E_GAS',
                'barcode' => '83640000000206701372025030500479102280030194',
                'value' => 20.67,
                'due_date' => '',
                'bank_code' => '836',
            ]
        ], readDigitableLine('836400000003206701372024503050047912022800301941'));
    }

    public function testBoletoConcessionariaGovernoLinhaDigitavel() {
        $this->assertEquals([
            'status' => true,
            'data' => [
                'type' => 2,
                'category' => 'ARRECADACAO_ORGAOS_GOVERNAMENTAIS',
                'barcode' => '85890000464524601791606075930508683148300001',
                'value' => 46452.46,
                'due_date' => '',
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
                'due_date' => '',
                'bank_code' => '858',
            ]
        ], readDigitableLine('85890000464524601791606075930508683148300001'));
    }

    public function testConversaoSemFormatacaoCodigoDeBarrasParaLinhaDigitavel()
    {
        $this->assertEquals('858200000007572503282030560708202107539591904460', formatBarcodeToDigitableLine('85820000000572503282035607082021053959190446'));
    }
}