<?php

require(__DIR__ . "/dev/Data/cp.inc.php");
require(__DIR__ . "/dev/Data/CodePointSeqsSets/getNewlineSeqsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSeqsSets/getWhitespaceSeqsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getASCIINameItemsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getASCIINameStartersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getBadURLRemnantsBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getDigitsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getEncodedCodePointEscapeSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getHexDigitsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getLCLettersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getLettersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNameItemsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNameStartersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNewlinesSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNonASCIIsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNonPrintablesSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getStringBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getStringDelimiterSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getUCLettersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getURLTokenBitDisallowedSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getURLTokenBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getValidEscapeStartersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getWhitespacesSet.inc.php");
require(__DIR__ . "/dev/Examples/ANY_UTF8.inc.php");
require(__DIR__ . "/tests/assertMatch.inc.php");
require(__DIR__ . "/tests/assertNotMatch.inc.php");
require(__DIR__ . "/tests/assertThrowsType.inc.php");
require(__DIR__ . "/tests/cartesianProduct.inc.php");
require(__DIR__ . "/tests/getCodePointsFromRanges.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/getTraverser.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/makeEatEscapeFunctionFromEscapeList.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/makePiecesSample.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatEscapeTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatIdentifierTokenFailingFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatIdentifierTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatNameTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatURLTokenFailingFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatURLTokenFunction.inc.php");
