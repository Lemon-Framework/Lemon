<?php

declare(strict_types=1);

namespace Lemon\Fixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class MissingFunctionExists extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('', []);
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound([T_FUNCTION]) && !$tokens->isAnyTokenKindsFound([T_CLASS]);
    }

    public function applyFix(SplFileInfo $file, Tokens $tokens): void
    { 
        $start = 0;
        while ($found = $tokens->findSequence([
            T_FUNCTION, T_STRING, Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, Tokens::BLOCK_TYPE_CURLY_BRACE
        ], $start)) {
            $name = array_values($found)[1]->getContent();
            $start = array_key_last($found);
            $pos = array_key_first($found) - 8;
            if (!$tokens->findSequence([
                T_IF, '(', 'function_exists', '(', $name, ')', ')', '{'
            ], $pos, $start)) {
                $tokens->insertAt($pos, [
                    new Token([T_IF, 'if']),
                    new Token('('),
                    new Token('function_exists'),
                    new Token('('),
                    new Token($name),
                    new Token(')'),
                    new Token(')'),
                    new Token('}'),
                ]);
            }
        }       
    }
}
