export default class UserSettingsAnimator {

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
}