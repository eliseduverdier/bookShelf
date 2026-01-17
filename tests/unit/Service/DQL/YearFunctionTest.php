<?php

declare(strict_types=1);

namespace App\Tests\unit\Service\DQL;

use App\Service\DQL\YearFunction;
use Doctrine\Common\Collections\ExpressionBuilder;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\CssSelector\Node\NodeInterface;

class YearFunctionTest extends TestCase
{
    protected YearFunction $yearFunction;
    protected SqlWalker $sqlWalker;

    protected function setUp(): void
    {
        $this->sqlWalker = $this->createMock(SqlWalker::class);

        $this->yearFunction = new YearFunction($this->sqlWalker);

        $node = $this->createMock(Node::class);
        $node->method('dispatch')->willReturn('result');
        $this->yearFunction->dateExpression = $node;
    }

    public function testYearFunctionGetSql(): void
    {
        self::assertEquals(
            'YEAR(result)',
            $this->yearFunction->getSql($this->sqlWalker)
        );
    }

    public function testYearFunctionParse(): void
    {
        $parser = $this->createMock(Parser::class);
        $parser
            ->method('ArithmeticPrimary')
            ->willReturn(
                new ParenthesisExpression(new YearFunction('year'))
            );
        $this->yearFunction->parse($parser);
        self::assertInstanceOf(ParenthesisExpression::class, $this->yearFunction->dateExpression);
        self::assertInstanceOf(YearFunction::class, $this->yearFunction->dateExpression->expression);

    }
}
