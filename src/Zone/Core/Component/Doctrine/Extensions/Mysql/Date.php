<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 11/14/2014
 * Time: 12:35 PM
 */

namespace Zone\Core\Component\Doctrine\Extensions\Mysql;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * "DATE" "(" SimpleArithmeticExpression ")".
 * Modified from DoctrineExtensions\Query\Mysql\Year
 *
 * More info:
 * http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date
 *
 * @category    DoctrineExtensions
 * @package     DoctrineExtensions\Query\Mysql
 * @author      Dawid Nowak <macdada@mmg.pl>
 * @license     MIT License
 */
class Date extends FunctionNode
{
    public $date;

    /**
     * @override
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'DATE('.$sqlWalker->walkArithmeticPrimary($this->date).')';
    }

    /**
     * @override
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->date = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}