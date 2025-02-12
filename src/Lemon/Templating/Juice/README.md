# Juice 2

## Main concepts

- parses html -- understands it, validates it, prevents csrf in most cases, allows crazy comunication between fe and be
- the parser is open for the user -- simple adding of new operators, directives
- the parser is complete, throws errors, generates good code

## Creating custom syntax

In order to create custom syntax, simply create the `Syntax` class and fill it with token descriptions where

- Each variable except `escape` is array of openning and closing token
- Openning token of directive and end directive should also include 1 matching group that features name of the directive, use `(?&DIRECTIVE_NAME)` for matching
- Openning tokens should be unique as opose to clossing tokens
- Keep in mind that its just tokens so except openning directive it should not use capturing groups and other high-tech stuff, dont worry, most stuff juice has covered
- Don't worry if your ending tag is also some expression syntax, juice has you covered
- All tokens are regexes that wont be quoted, they will be used as they are so dont forget to escape regex tokes inf you are not using them for regex

finished class pass *somewhere idk where todo*

## ideas

mby directives/operators could be straight up nodes
