"use strict";

import Register from "./Classes/Register.js";

let register = new Register();

register.defuseInitialErrorBox();

register.nextButton("#hideUserdata", ["newUsername", "newUserPw"], "newUserdataContainer", "wealthDistribution");

register.additionalWDInput();
register.nextButton("#hideWealthDist", ["wealthdist"], "wealthDistribution", "revenueCategories");
register.backButton("#showUserdata", "newUserdataContainer", "wealthDistribution");

register.additionalInput("#anotherRevCat", "revenueCategories", "revcat", "e.g. Salary");
register.nextButton("#hideRevenueCategories", ["revcat"], "revenueCategories", "expenditureCategories");
register.backButton("#showExpenditureCategories", "expenditureCategories", "goals");

// #TODO: Hinweis, wie Donations gefunden werden
register.additionalInput("#anotherExpCat", "expenditureCategories", "expcat", "e.g. Rent");
register.nextButton("#hideExpenditureCategories", ["expcat"], "expenditureCategories", "goals");
register.backButton("#showWealthDist", "wealthDistribution", "revenueCategories");

register.backButton("#showRevenueCategories", "revenueCategories", "expenditureCategories");