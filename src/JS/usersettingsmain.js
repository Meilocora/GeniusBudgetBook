"use strict";

import UserSettingsAnimator from "./Classes/UserSettingsAnimator.js";

let animator = new UserSettingsAnimator();

// Edit username area
animator.titleTransition("title_changeUsername", "usernameForm");

// Edit password area
animator.titleTransition("title_changePassword", "passwordForm");

// Change color theme area
animator.titleTransition("title_changeColorTheme", "standardColorForm");

// Create custom color theme area
animator.titleTransition("title_colorTheme", "customColorForm");

// Create custom chart color theme area
animator.titleTransition("title_chartColorTheme", "customChartColorForm");

// Edit wealth distribution categories area
animator.titleTransition("title_wdcats", "wdForm");
animator.dummyDelete("dummyWDCatDelete", "disclaimerDeleteWDcat", "abortWDCat");
animator.dummySend("dummyWDConfirm", "hintWD", "abortWDCat");

// Edit revenue categories area
animator.titleTransition("title_revcats", "revcatForm");
animator.dummyDelete("dummyRevCatDelete", "disclaimerDeleteRevcat", "abortRevCat");
animator.dummySend("dummyRevCatConfirm", "hintRevcat", "abortRevCat");

// Edit expenditure categories area
animator.titleTransition("title_expcats", "expcatForm");
animator.dummyDelete("dummyExpCatDelete", "disclaimerDeleteExpcat", "abortExpCat");
animator.dummySend("dummyExpCatConfirm", "hintExpcat", "abortExpCat");
