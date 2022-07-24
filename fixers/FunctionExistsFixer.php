<?php

declare(strict_types=1);

namespace Lemon\Fixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class FunctionExistsFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('', []);
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound([T_FUNCTION]) && !$tokens->isAnyTokenKindsFound([T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM]);
    }

    public function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        // TODO
        /*
        $start = 0;
        while($found = $tokens->findSequence([
            [T_FUNCTION],
            [T_STRING],
        ], $start)) {
            $values = array_values($found);
            $name = $values[1]->getContent();

            $sequence = [
                new Token([T_IF, 'if']),
                new Token('('),
                new Token('!'),
                new Token('function_exists'),
                new Token('('),
                new Token([T_CONSTANT_ENCAPSED_STRING, "'$name'"]),
                new Token(')'),
                new Token(')'),
                new Token('{'),
                new Token("\n"),
            ];
            $pos = array_key_first($found);
            $end = array_key_last($found);
            $end = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, array_key_first($tokens->findSequence(['{'], $end))); 
            if (!$tokens->findSequence($sequence, $pos - 9, $end)) {
                $tokens->insertAt($end, [new Token("\n"), new Token('}')]);
                $tokens->insertAt($pos, $sequence);
            }
            $start = $end;
        }
        */
    }

    public function getName(): string
    {
        return 'Lemon/function_exists';
    }
}
