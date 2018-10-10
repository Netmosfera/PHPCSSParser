<?php

require(__DIR__ . "/dev/Data/cp.inc.php");
require(__DIR__ . "/dev/Data/CodePointSeqsSets/getNewlineSeqsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSeqsSets/getWhitespaceSeqsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getBadURLRemnantsBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getDigitsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getEncodedCodePointEscapeSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getHexDigitsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNameItemsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNameStartersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNewlinesSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNonASCIIsSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getNonPrintablesSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getStringBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getStringDelimiterSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getURLTokenBitDisallowedSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getURLTokenBitSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getValidEscapeStartersSet.inc.php");
require(__DIR__ . "/dev/Data/CodePointSets/getWhitespacesSet.inc.php");
require(__DIR__ . "/dev/Examples/ANY_UTF8.inc.php");
require(__DIR__ . "/dev/FastTokenizer/keyze.inc.php");
require(__DIR__ . "/dev/FastTokenizer/verifyGroupsDoNotOverlap.inc.php");
require(__DIR__ . "/dev/FastTokenizer/verifyUnicodeCovered.inc.php");
require(__DIR__ . "/tests/assertMatch.inc.php");
require(__DIR__ . "/tests/assertNotMatch.inc.php");
require(__DIR__ . "/tests/assertThrowsType.inc.php");
require(__DIR__ . "/tests/cartesianProduct.inc.php");
require(__DIR__ . "/tests/getSampleCodePointsFromRanges.inc.php");
require(__DIR__ . "/tests/groupByOffset.inc.php");
require(__DIR__ . "/tests/makePiecesSample.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/getTraverser.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatEscapeTokenFailingFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatEscapeTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatIdentifierTokenFailingFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatIdentifierTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatNameTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatNumberTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatURLTokenFailingFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatURLTokenFunction.inc.php");
require(__DIR__ . "/tests/StandardTokenizer/Fakes/eatValidEscapeTokenFunction.inc.php");
require(__DIR__ . "/tests/TokensChecked/makeBadURLRemnantsPieceAfterPieceFunction.inc.php");
require(__DIR__ . "/tests/TokensChecked/makeIdentifierPieceAfterPieceFunction.inc.php");
require(__DIR__ . "/tests/TokensChecked/makeStringPieceAfterPieceFunction.inc.php");
require(__DIR__ . "/tests/TokensChecked/makeURLPieceAfterPieceFunction.inc.php");
require(__DIR__ . "/tests/TokensChecked/piecesIntendedValue.inc.php");
require(__DIR__ . "/tests/TokensChecked/sampleURLIdentifiers.inc.php");
