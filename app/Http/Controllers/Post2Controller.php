<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Post2Controller extends Controller
{
    public function index(Request $request): Response
    {
        $queryParam = 'AND(OR(EQUAL(id,"first-post"),AND(EQUAL(id,"second-post"),GREATER_THAN(view,100))),NOT(EQUAL(id,"third-post")))';

        $initQuery = "select * from `posts`";

        $tree  = $this->buildTree($queryParam);
        $query = $this->compileQuery($tree);

        dd($initQuery . ' ' . $query);
        DB::select($initQuery . ' ' . $query);
    }

    private array $valuesBinding = [];

    private const OPERATORS = [
        "EQUAL"        => ["operator" => "=", "type" => "RELATIONAL", "operandCount" => 2],
        "AND"          => ["operator" => "AND", "type" => "LOGICAL", "operandCount" => 2],
        "OR"           => ["operator" => "OR", "type" => "LOGICAL", "operandCount" => 2],
        "NOT"          => ["operator" => "NOT", "type" => "LOGICAL", "operandCount" => 1],
        "GREATER_THAN" => ["operator" => ">", "type" => "RELATIONAL", "operandCount" => 2],
        "LESS_THAN"    => ["operator" => "<", "type" => "RELATIONAL", "operandCount" => 2],
    ];


    private array $stack = [];

    private function buildTree(string $queryParam)
    {
        $isValidTree = false;
        $node        = [];
        foreach (self::OPERATORS as $symbol => $operatorInfo) {
            if (str_starts_with($queryParam, $symbol)) {
                preg_match("/$symbol\((.*?)\)$/", $queryParam, $match);

                if (!isset($match[1])) {
                    dd("invalid queryParam", $queryParam);
                }

                $subParam = $match[1];

                $isValidTree          = true;
                $node['value']        = $operatorInfo["operator"];
                $node['operandCount'] = $operatorInfo["operandCount"];

                if ("RELATIONAL" === $operatorInfo['type']) {
                    foreach (explode(",", $subParam) as $leaf) {
                        $node['children'][] = ["value" => $leaf];
                    }

                    break;
                }

                foreach ($this->findChildren($subParam) as $param) {
                    $node["children"][] = $this->buildTree($param);
                }
            }
        }

        if (!$isValidTree) {
            dd("not valid tree", $queryParam);
        }

        return $node;
    }

    private function findChildren(string $queryParam): array
    {
        $children = [];
        for ($i = 0; $i < strlen($queryParam); $i++) {
            if ("(" == $queryParam[$i]) {
                $this->stack[] = $queryParam[$i];
            }

            if (")" == $queryParam[$i]) {
                array_pop($this->stack);
                if (empty($this->stack)) {
                    $children[] = substr($queryParam, 0, $i + 1);
                    if (isset($queryParam[$i + 2])) {
                        $children[] = substr($queryParam, $i + 2);
                    }
                    break;
                }
            }
        }
        $this->stack = [];

        return $children;
    }

    public function compileQuery(array $node): string
    {
        foreach ($this->postOrder($node) as $node) {
            $list[] = $stackItem = $node['value'];
            if ($node['type'] == "OPERATOR") {
                if ($node['operandCount'] == 1) {
                    $stackItem = "( " . $node['value'] . " " . array_pop($this->stack) . " )";
                } else {
                    $firstOperand  = array_pop($this->stack);
                    $secondOperand = array_pop($this->stack);
                    $stackItem     = "( " . $secondOperand . " " . $node['value'] . " " . $firstOperand . " )";
                }
            }
            array_push($this->stack, $stackItem);
        }

        return array_pop($this->stack);
    }

    function postOrder(array $node)
    {
        foreach ($node['children'] ?? [] as $child) {
            yield from $this->postOrder($child);
        }

        yield $this->visit($node);
    }

    private function visit(array $node): array
    {
        return [
            'value'        => $node['value'],
            'operandCount' => $node['operandCount'] ?? null,
            'type'         => isset($node['children']) && !empty($node['children']) ? "OPERATOR" : "LEAF",
        ];
    }
}
