"use strict";

import FrontendAnimator from "./Classes/FrontendAnimator.js";

let animator = new FrontendAnimator();

let switchClass = "hidden"
animator.titleTransition("cTitle", "searchSettingsForm");

// Timeinterval
animator.classMainSwitcher2(switchClass, "cCustom", "fromDateSpan", "toDateSpan");
animator.classSideSwitcher2(switchClass, "cAll", "fromDateSpan", "toDateSpan");
animator.classSideSwitcher2(switchClass, "cYoY", "fromDateSpan", "toDateSpan");
animator.classSideSwitcher2(switchClass, "cYTD", "fromDateSpan", "toDateSpan");

// CATEGORY
animator.classMainSwitcher1(switchClass, "cCertainCategory", "categorySpan");
animator.classSideSwitcher1(switchClass, "cAllCategories", "categorySpan");

// TITLE
animator.classMainSwitcher1(switchClass, "cCertainTitle", "titleSpan");
animator.classSideSwitcher1(switchClass, "cAllTitles", "titleSpan");

// AMOUNT
animator.classMainSwitcher1(switchClass, "cCustomAmount", "amountSpan");
animator.classSideSwitcher1(switchClass, "cAllAmounts", "amountSpan");

// COMMENT
animator.classMainSwitcher1(switchClass, "cCertainComment", "commentSpan");
animator.classSideSwitcher1(switchClass, "cNoComments", "commentSpan");
animator.classSideSwitcher1(switchClass, "cAllComments", "commentSpan");