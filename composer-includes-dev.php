<?php

require(__DIR__ . "/dev/cp.inc.php");
require(__DIR__ . "/dev/Examples/ANY_UTF8.inc.php");
require(__DIR__ . "/dev/Examples/COMMENT_TEXTS.inc.php");
require(__DIR__ . "/dev/Examples/NOT_A_NUMBER_CONTINUATION_AFTER_DECIMAL_PART.inc.php");
require(__DIR__ . "/dev/Examples/NOT_A_NUMBER_CONTINUATION_AFTER_E_PART.inc.php");
require(__DIR__ . "/dev/Examples/NOT_A_NUMBER_CONTINUATION_AFTER_INTEGER_PART.inc.php");
require(__DIR__ . "/dev/Examples/NOT_STARTING_WITH_COMMENT_START.inc.php");
require(__DIR__ . "/dev/Examples/NOT_STARTING_WITH_WHITESPACE.inc.php");
require(__DIR__ . "/dev/Examples/ONE_OR_MORE_DIGITS.inc.php");
require(__DIR__ . "/dev/Examples/ONE_TO_SIX_HEX_DIGITS.inc.php");
require(__DIR__ . "/dev/Examples/OPTIONAL_NUMBER_SIGN.inc.php");
require(__DIR__ . "/dev/Examples/WHITESPACES.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSeqsSets/getNewlineSeqsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSeqsSets/getWhitespaceSeqsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getDigitsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getHexDigitsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getLCLettersSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getLettersSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getNameItemsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getNameStartersSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getNewlinesSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getNonASCIIsSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getNonPrintablesSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getStringDelimiterSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getUCLettersSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getValidEscapesSet.inc.php");
require(__DIR__ . "/dev/SpecData/CodePointSets/getWhitespacesSet.inc.php");
require(__DIR__ . "/tests/assertTokensMatch.inc.php");
require(__DIR__ . "/tests/cartesianProduct.inc.php");
require(__DIR__ . "/tests/getBoundariesCount.inc.php");
require(__DIR__ . "/tests/getBoundariesRange.inc.php");
require(__DIR__ . "/tests/getCodePointsFromRanges.inc.php");
