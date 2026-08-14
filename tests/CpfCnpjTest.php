<?php
use PHPUnit\Framework\TestCase;

final class CpfCnpjTest extends TestCase
{
    /**Exemplo oficial do Manual de Cálculo do DV do CNPJ Alfanumérico (Receita Federal) */
    private const CNPJ_ALFANUMERICO = '12ABC34501DE35';
    private const CNPJ_NUMERICO = '11222333000181';
    private const CPF = '52998224725';

    public function testOnlyCpfCnpjMantemLetrasERemoveMascara()
    {
        $this->assertEquals(self::CNPJ_ALFANUMERICO, onlyCpfCnpj('12.ABC.345/01DE-35'));
    }

    public function testOnlyCpfCnpjConverteParaMaiusculo()
    {
        $this->assertEquals(self::CNPJ_ALFANUMERICO, onlyCpfCnpj('12.abc.345/01de-35'));
    }

    public function testOnlyCpfCnpjRetornaNullQuandoVazio()
    {
        $this->assertNull(onlyCpfCnpj('./-'));
        $this->assertNull(onlyCpfCnpj(''));
        $this->assertNull(onlyCpfCnpj(null));
    }

    public function testCnpjCharValueSegueTabelaAscii()
    {
        $this->assertEquals(0, cnpjCharValue('0'));
        $this->assertEquals(9, cnpjCharValue('9'));
        $this->assertEquals(17, cnpjCharValue('A'));
        $this->assertEquals(42, cnpjCharValue('Z'));
    }

    public function testCnpjCharValueAceitaMinusculo()
    {
        $this->assertEquals(17, cnpjCharValue('a'));
    }

    public function testCalcCnpjDvDeCnpjAlfanumerico()
    {
        $this->assertEquals('35', calcCnpjDv('12ABC34501DE'));
    }

    public function testCalcCnpjDvIgnoraDvJaInformado()
    {
        $this->assertEquals('35', calcCnpjDv(self::CNPJ_ALFANUMERICO));
    }

    public function testCalcCnpjDvDeCnpjNumerico()
    {
        $this->assertEquals('81', calcCnpjDv('112223330001'));
    }

    public function testCalcCnpjDvRetornaNullComBaseIncompleta()
    {
        $this->assertNull(calcCnpjDv('12ABC345'));
    }

    public function testIsValidCnpjAceitaAlfanumerico()
    {
        $this->assertTrue(isValidCnpj(self::CNPJ_ALFANUMERICO));
        $this->assertTrue(isValidCnpj('12.ABC.345/01DE-35'));
        $this->assertTrue(isValidCnpj('12abc34501de35'));
    }

    public function testIsValidCnpjAceitaNumerico()
    {
        $this->assertTrue(isValidCnpj(self::CNPJ_NUMERICO));
        $this->assertTrue(isValidCnpj('11.222.333/0001-81'));
    }

    public function testIsValidCnpjRecusaDvIncorreto()
    {
        $this->assertFalse(isValidCnpj('12ABC34501DE34'));
        $this->assertFalse(isValidCnpj('11222333000180'));
    }

    public function testIsValidCnpjRecusaDvComLetra()
    {
        $this->assertFalse(isValidCnpj('12ABC34501DEA5'));
    }

    public function testIsValidCnpjRecusaTamanhoInvalido()
    {
        $this->assertFalse(isValidCnpj('12ABC34501DE355'));
        $this->assertFalse(isValidCnpj('12ABC34501DE3'));
        $this->assertFalse(isValidCnpj(''));
    }

    public function testIsValidCnpjRecusaCaractereRepetido()
    {
        $this->assertFalse(isValidCnpj('00000000000000'));
        $this->assertFalse(isValidCnpj('11111111111111'));
        $this->assertFalse(isValidCnpj('AAAAAAAAAAAAAA'));
    }

    public function testIsValidCnpjAceitaSequenciaDoExterior()
    {
        $this->assertTrue(isValidCnpj('99999999999999'));
    }

    public function testIsValidCnpjCompletaZerosAEsquerdaApenasQuandoNumerico()
    {
        $this->assertTrue(isValidCnpj('11222333000181'));
        $this->assertFalse(isValidCnpj('ABC34501DE35'));
    }

    public function testIsValidCpf()
    {
        $this->assertTrue(isValidCpf(self::CPF));
        $this->assertTrue(isValidCpf('529.982.247-25'));
        $this->assertFalse(isValidCpf('52998224724'));
        $this->assertFalse(isValidCpf('11111111111'));
    }

    public function testIsValidCpfCnpjRoteiaPeloTamanho()
    {
        $this->assertTrue(isValidCpfCnpj(self::CPF));
        $this->assertTrue(isValidCpfCnpj(self::CNPJ_NUMERICO));
        $this->assertTrue(isValidCpfCnpj('12.ABC.345/01DE-35'));
        $this->assertFalse(isValidCpfCnpj('123'));
    }

    public function testIsCnpjAlfanumerico()
    {
        $this->assertTrue(isCnpjAlfanumerico(self::CNPJ_ALFANUMERICO));
        $this->assertTrue(isCnpjAlfanumerico('12.ABC.345/01DE-35'));
        $this->assertFalse(isCnpjAlfanumerico(self::CNPJ_NUMERICO));
    }

    public function testFormatCnpjAplicaMascaraEmAlfanumerico()
    {
        $this->assertEquals('12.ABC.345/01DE-35', formatCnpj(self::CNPJ_ALFANUMERICO));
        $this->assertEquals('12.ABC.345/01DE-35', formatCnpj('12.abc.345/01de-35'));
    }

    public function testFormatCnpjMantemComportamentoNumerico()
    {
        $this->assertEquals('11.222.333/0001-81', formatCnpj(self::CNPJ_NUMERICO));
    }

    public function testFormatCpfCnpjRoteiaAlfanumericoParaCnpj()
    {
        $this->assertEquals('12.ABC.345/01DE-35', formatCpfCnpj(self::CNPJ_ALFANUMERICO));
        $this->assertEquals('529.982.247-25', formatCpfCnpj(self::CPF));
    }

    public function testOnlyDfeKeyRemovePrefixoEMascara()
    {
        $chave = '352608' . self::CNPJ_ALFANUMERICO . '550010000000011100000019';

        $this->assertEquals($chave, onlyDfeKey('NFe' . $chave));
        $this->assertEquals($chave, onlyDfeKey('CTe' . $chave));
        $this->assertEquals($chave, onlyDfeKey(strtolower('nfe' . $chave)));
    }

    public function testIsValidDfeKeyAceitaChaveComCnpjAlfanumerico()
    {
        $this->assertTrue(isValidDfeKey('352608' . self::CNPJ_ALFANUMERICO . '550010000000011100000019'));
        $this->assertTrue(isValidDfeKey('NFe352608' . self::CNPJ_ALFANUMERICO . '550010000000011100000019'));
    }

    public function testIsValidDfeKeyAceitaChaveNumerica()
    {
        $this->assertTrue(isValidDfeKey('352608' . self::CNPJ_NUMERICO . '550010000000011100000019'));
    }

    public function testIsValidDfeKeyRecusaLetraForaDoCnpj()
    {
        $this->assertFalse(isValidDfeKey('352608' . self::CNPJ_ALFANUMERICO . 'A50010000000011100000019'));
        $this->assertFalse(isValidDfeKey('35260A' . self::CNPJ_ALFANUMERICO . '550010000000011100000019'));
    }

    public function testIsValidDfeKeyRecusaTamanhoInvalido()
    {
        $this->assertFalse(isValidDfeKey('352608' . self::CNPJ_ALFANUMERICO));
        $this->assertFalse(isValidDfeKey(''));
    }
}
