export default class FrontendAnimator {

    titleTransition(titleId, formId) {
        document.getElementById(titleId).addEventListener("click", e => {
            document.getElementById(formId).classList.toggle("hide");
            document.getElementById(titleId).classList.toggle("titleSelected");
         });
    }

    dummyDelete(dummyBtnId, disclaimerId, abortBtnId) {
        for ( let i=1; i<=10; i++ ) {
            document.getElementById(`${dummyBtnId}${i}`).addEventListener("click", e => {
                e.preventDefault();
                document.getElementById(`${disclaimerId}${i}`).classList.toggle("hidden");
                });
                document.getElementById(`${abortBtnId}${i}`).addEventListener("click", e => {
                    e.preventDefault();
                    document.getElementById(`${disclaimerId}${i}`).classList.toggle("hidden");
            });
        };
    }

    dummySend(dummyBtnId, hintId, abortBtnId) {
        document.getElementById(dummyBtnId).addEventListener("click", e => {
            e.preventDefault();
            document.getElementById(hintId).classList.toggle("hidden");
            });
            document.getElementById(abortBtnId).addEventListener("click", e => {
                e.preventDefault();
                document.getElementById(hintId).classList.toggle("hidden");
            });
    }

    classMainSwitcher1(switchClass, idSwitch, idTarget) {
        document.getElementById(idSwitch).addEventListener("click", e => {
            if(document.getElementById(idSwitch).checked & document.getElementById(idTarget).classList.contains(switchClass)) {
                document.getElementById(idTarget).classList.toggle(switchClass);
            }
        });
    }

    classSideSwitcher1(switchClass, idSwitch, idTarget) {
        document.getElementById(idSwitch).addEventListener("click", e => {
            if(!document.getElementById(idTarget).classList.contains(switchClass)) {
                document.getElementById(idTarget).classList.toggle(switchClass);
            }
        });
    }

    classMainSwitcher2(switchClass, idSwitch, idTarget1, idTarget2) {
        document.getElementById(idSwitch).addEventListener("click", e => {
            if(document.getElementById(idSwitch).checked & document.getElementById(idTarget1).classList.contains(switchClass) & document.getElementById(idTarget2).classList.contains(switchClass)) {
                document.getElementById(idTarget1).classList.toggle(switchClass);
                document.getElementById(idTarget2).classList.toggle(switchClass);
            }
        });
    }

    classSideSwitcher2(switchClass, idSwitch, idTarget1, idTarget2) {
        document.getElementById(idSwitch).addEventListener("click", e => {
            if(!document.getElementById(idTarget1).classList.contains(switchClass) & !document.getElementById(idTarget2).classList.contains(switchClass)) {
                document.getElementById(idTarget1).classList.toggle(switchClass);
                document.getElementById(idTarget2).classList.toggle(switchClass);
            }
        });
    }

}