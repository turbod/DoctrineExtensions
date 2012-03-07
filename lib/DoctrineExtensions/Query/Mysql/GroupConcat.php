<?php
/**
 *
 *
 * PHP version 5
 *
 * @category
 * @package
 * @subpackage
 * @author     Tóth Norbert <tothnorbert.zalalovo@gmail.com>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 * @link       www.tothnorbert.co.cc
 */
namespace Konstruktor\TradeBundle\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;


class GroupConcat extends FunctionNode
{
    public $isDistinct = false;
    public $expression = null;

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GROUP_CONCAT(' .
            ($this->isDistinct ? 'DISTINCT ' : '') .
            $this->expression->dispatch($sqlWalker) .
            ' ' . $this->orderExpression->dispatch($sqlWalker) .
        ')';
    }//end getSql()


    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_DISTINCT)) {
            $parser->match(Lexer::T_DISTINCT);

            $this->isDistinct = true;
        }

        $this->expression = $parser->SingleValuedPathExpression();
        $parser->match(Lexer::T_COMMA);
        $this->orderExpression = $parser->OrderByClause();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }//end parse()

}//end class