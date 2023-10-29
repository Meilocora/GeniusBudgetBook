export default class Register {

    constructor(usernames) {
        this._usernames = [usernames];
    }
    
    defuseInitialErrorBox() {
        let errorBox = document.querySelector(".error-box, .registry-error");
        if(errorBox !== null) {
            errorBox.addEventListener("click", function() {
                errorBox.style.display = "none";
            });
        }
        
    }
    
    nextButton(selector, inputRegexArray, toggle1, toggle2) {
        document.querySelector(selector).addEventListener("click", e => {
            e.preventDefault();
            let inputsFilledResult = this._inputsFilled(inputRegexArray);
            if(inputsFilledResult instanceof Map) {
                let validationResult = this._validate(inputsFilledResult);
                if (validationResult === true) {
                    document.getElementById(toggle1).classList.toggle("hidden");
                    document.getElementById(toggle2).classList.toggle("hidden");
                } else {
                    this._errorMessages([], validationResult)
                }
            } else {
                this._errorMessages(inputsFilledResult, []);
            }
        });
    }

    _inputsFilled(inputRegexArray) {
        let inputs = new Map();
        let emptyInputs = [];
        let singleInput;
        inputRegexArray.forEach(e => {
            singleInput = document.getElementById(`${e}`);
            if(singleInput !== null) {
                singleInput = singleInput.value;
                if (singleInput != '') {
                    inputs.set(e, singleInput);
                } else {
                    emptyInputs.push(e);
                }
            }
            for (let i=1; i<11; i++) {
                let input = document.getElementById(`${e}${i}`);
                if(input !== null) {
                    if (input.value !== "") {
                        inputs.set(`${e}${i}`, input.value);
                    } else {
                        emptyInputs.push(`${e}${i}`);
                    }
                }
            }
        });
        if(emptyInputs.length === 0) {
            return inputs;
        } else {
            return emptyInputs;
        }
        }

    _validate(inputsFilled) {
        let errorArray = [];
        let regexArrayLogin = [
            "newUsername",
            "newUserPw"
        ];
        let regexArrayInput = [
            "wealthdist",
            "revcat",
            "expcat"
        ];
        let keys = Array.from(inputsFilled.keys());
        keys.forEach(key => {
            regexArrayLogin.forEach(regexLogin => {
                if(key.match(regexLogin)) {
                    errorArray = errorArray.concat(this._validateLoginData(key, inputsFilled.get(key)));
                }
            })
            regexArrayInput.forEach(regexInput => {
                if(key.match(regexInput)) {
                    errorArray = errorArray.concat(this._validateInputs(inputsFilled))
                }
            })
        
        })
        if(errorArray.length === 0) {
            return true;
        } else {
            return errorArray;
        }
    }

    _validateLoginData(key, value) {
        let errorArray = []; 
        if(key === "newUsername") {
            if(!value.match(/^.{4,30}$/)) errorArray.push("- username must include 4 to 30 characters");
            if(value.match(/^\d.*?/)) errorArray.push("- username can`t begin with a number");
            if(value.match(/[\.\^\$\*\+\-\?\(\)\[\]\{\}\\\|]/)) errorArray.push("- you can`t use: . ^ $ * + - ? ( ) [ ] { } \ |");
            if(!value.match(/^[^\ ].*[^\ .]$/)) errorArray.push("- username can't start or end with blank space");
            this._usernames[0].forEach(element => {
                if (element == value) errorArray.push("- username already exists!");
            });
        }
        if(key === "newUserPw") {
            // if(!value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,60}$/)) errorArray.push("- password must include 8 to 60 characters, at least one uppercase letter, one lowercase letter, one number and one special character");
            if(!value.match(/^.{6,60}$/)) errorArray.push("- password must include 8 to 60 characters");
            if(!value.match(/^.*(?=.*[a-z])[A-Za-z\d@$!%*?&].*$/)) errorArray.push("- password must include at least one lowercase letter");
            if(!value.match(/^.*(?=.*[A-Z])[A-Za-z\d@$!%*?&].*$/)) errorArray.push("- password must include at least one uppercase letter");
            if(!value.match(/^.*(?=.*\d)[A-Za-z\d@$!%*?&].*$/)) errorArray.push("- password must include at least one number ");
            if(!value.match(/^.*(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].*$/)) errorArray.push("- password must include at least one special character");
            if(!value.match(/^.*[^\ ].*[^\ .].*$/)) errorArray.push("- password can't start or end with blank space.");
            
        }       
        return errorArray;
    }

    _validateInputs(inputsFilled) {
        let regexArray = [
            "wealthdist",
            "revcat",
            "expcat"
        ];
        let errorArray = [];
        let duplicateErrorArray = this._validateDuplicates(inputsFilled);
        if(duplicateErrorArray !== null) {
            errorArray = errorArray.concat(duplicateErrorArray);
        }
        regexArray.forEach(e => {
            for(let i=1; i<11; i++) {
                let input = inputsFilled.get(`${e}${i}`);
                if(typeof input !== "undefined") {
                    if(!input.match(/^.{3,20}$/)) errorArray.push(`The name of the category "${input}" can include at least 3 and maximum 20 symbols`);  
                } 
            }
        }); 
        return errorArray;
    }

    _validateDuplicates(inputsFilled) {
        let duplicateErrorArray = [];
        let values = Array.from(inputsFilled.values());
        let duplicates = this._findDuplicates(values);
        if(duplicates.length > 0) {
            for(let i=0; i<duplicates.length; i++) {
                duplicateErrorArray.push(`You can use the Category "${duplicates[i]}" only once.`);
            }  
            return duplicateErrorArray;
        } else {
            return null;
        }
    }

    _findDuplicates(arr) {
        return arr.filter((currentValue, currentIndex) =>
            arr.indexOf(currentValue) !== currentIndex);
    }

    _errorMessages(emptyInputs, validationErrors) {
        let errorArray = [];
        if(emptyInputs.length > 0) {
            let errorTitle = "Looks like you forgot:";
            emptyInputs.forEach(e => {
                if(e === "newUsername") errorArray.push("- username");
                if(e === "newUserPw") errorArray.push("- password");
                for(let i=1; i<11; i++) {
                    if(e === `wealthdist${i}`) errorArray.push(`- Category ${i}`);
                    if(e === `revcat${i}`) errorArray.push(`- Category ${i}`);
                    if(e === `expcat${i}`) errorArray.push(`- Category ${i}`);
                }
            });
            this._createErrorBox(errorTitle, errorArray);
        }
        if(validationErrors.length > 0) {
            let errorTitle = "There are some problems with you inputs:";
            let uniqArray = [...new Set(validationErrors)];
            uniqArray.forEach(error => { 
                errorArray.push(error);
            })
            this._createErrorBox(errorTitle, errorArray);
        }   
    }

    _createErrorBox(errorTitle, errorArray) {
        let errorBox = document.createElement("div");
        errorBox.setAttribute("class", "error-box registry-error");

        let errorSymbol = document.createElement("img");
        errorSymbol.setAttribute("src", "./img/error.png");
        errorSymbol.setAttribute("alt", "Error Symbol");
        errorSymbol.setAttribute("height", "70px");
        errorSymbol.setAttribute("width", "70px");
        errorBox.insertAdjacentElement("afterbegin", errorSymbol);
        
        let errorSpanTitle = document.createElement("h2");
        errorSpanTitle.textContent = errorTitle;
        errorSymbol.insertAdjacentElement("afterend", errorSpanTitle);

        errorArray.forEach(error => {
            let errorSpan = document.createElement("span");
            errorSpan.textContent = error;
            errorBox.insertAdjacentElement("beforeend", errorSpan);

            let wordwrap = document.createElement("br");
            errorSpan.insertAdjacentElement("afterend", wordwrap);
        });

        let mainTitle = document.getElementById("mainTitle");
        mainTitle.insertAdjacentElement("afterend", errorBox);

        errorBox.addEventListener("click", function() {
            errorBox.style.display = "none";
        });
    }

    backButton(selector, toggle1, toggle2) {
        document.querySelector(selector).addEventListener("click", e => {
            e.preventDefault();
            document.getElementById(toggle1).classList.toggle("hidden");
            document.getElementById(toggle2).classList.toggle("hidden");
        });
    }
    
    additionalWDInput() {
        document.querySelector("#anotherWealthDistCat").addEventListener("click", e => {
            e.preventDefault();
            let div = document.getElementById('wealthDistribution');
            let totalCategories = div.querySelectorAll("input[type=text]").length;
            let x = totalCategories+1;
            if(document.getElementById(`wealthdist${x-1}`).value != "") {
                if(totalCategories < 10) {
                    let row = document.createElement("div");
                    row.setAttribute("class", "row-register")
    
                    let lastCheckbox = document.getElementById(`wd${x-1}liquid`);
                    let lastRow = lastCheckbox.parentElement;
                    lastRow.insertAdjacentElement("afterend", row);
    
                    let label = document.createElement("label");
                    label.setAttribute("for", `wealthdist${x}`);
                    label.textContent = `Category ${x}: `;
                    row.insertAdjacentElement("afterbegin", label);
    
                    let textInput = document.createElement("input");
                    textInput.setAttribute("type", "text");
                    textInput.setAttribute("name", `wealthdist${x}`);
                    textInput.setAttribute("id", `wealthdist${x}`);
                    textInput.setAttribute("placeholder", "e.g. Bank account");
                    textInput.setAttribute("maxlength", "20");
                    label.insertAdjacentElement("afterend", textInput);
                
                    let checkboxInput = document.createElement("input");
                    checkboxInput.setAttribute("type", "checkbox");
                    checkboxInput.setAttribute("name", `wd${x}liquid`);
                    checkboxInput.setAttribute("id", `wd${x}liquid`);
                    checkboxInput.setAttribute("value", "1");
                    textInput.insertAdjacentElement("afterend", checkboxInput); 
    
                    let questionmark = document.createElement("img");
                    questionmark.setAttribute("src", "./img/questionmark.png");
                    questionmark.setAttribute("height", "12px");
                    questionmark.setAttribute("width", "12px");
                    questionmark.setAttribute("alt", "Questionmark symbol.");
                    checkboxInput.insertAdjacentElement("afterend", questionmark);
    
                    let explanation = document.createElement("span");
                    explanation.setAttribute("class", "hidden");
                    explanation.textContent = `Check to add this category to liquid assets. Liquid assets will be added to your savings balance.`;
                    questionmark.insertAdjacentElement("afterend", explanation);
                    
                    if(totalCategories === 9) {
                        document.getElementById("anotherWealthDistCat").classList.toggle("hidden");
                    }
                }
            }
            else {
                this._errorMessages([`wealthdist${x-1}`], []);
            }
        });
    }
    
    additionalInput(selector, container, category, placeholderString) {
        document.querySelector(selector).addEventListener("click", e => {
            e.preventDefault();
            let div = document.getElementById(container);
            let totalCategories = div.querySelectorAll("input[type=text]").length;
            let x = totalCategories+1;
            if(document.getElementById(`${category}${x-1}`).value != "") {
                if(totalCategories < 10) {
                    let row = document.createElement("div");
                    row.setAttribute("class", "row-register")
        
                    let lastInput = document.getElementById(`${category}${x-1}`);
                    let lastRow = lastInput.parentElement;
                    lastRow.insertAdjacentElement("afterend", row);
        
                    let label = document.createElement("label");
                    label.setAttribute("for", `${category}${x}`);
                    label.textContent = `Category  ${x}: `;
                    row.insertAdjacentElement("afterbegin", label);
        
                    let textInput = document.createElement("input");
                    textInput.setAttribute("type", "text");
                    textInput.setAttribute("name", `${category}${x}`);
                    textInput.setAttribute("id", `${category}${x}`);
                    textInput.setAttribute("placeholder", placeholderString);
                    label.insertAdjacentElement("afterend", textInput);
                    if(totalCategories === 9) {
                        document.getElementById(selector).classList.toggle("hidden");
                    }
                }
            }
            else {
                this._errorMessages([`${category}${x-1}`], []);
            }
        });
    }
}

