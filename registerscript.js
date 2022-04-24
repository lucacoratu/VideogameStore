/* Multistep variables initialization */
const multiStepForm = document.querySelector("[data-multi-step]")
const formSteps = [...multiStepForm.querySelectorAll("[data-step]")]

/* Progress bar objects */
const pbar1 = document.getElementById("first-progress")
const pbar2 = document.getElementById("second-progress")

/* Number labels */
const number2 = document.getElementById("number2")
const number3 = document.getElementById("number3")

/* console.log(number2) */

let currentStep = formSteps.findIndex(step => {
    return step.classList.contains("active")
})

/* console.log(currentStep) */

if(currentStep < 0){
    currentStep = 0
    showCurrentStep()
}

multiStepForm.addEventListener("click", e => {
    let increment
    if(e.target.matches("[data-next]")){
        increment = 1
    }else if (e.target.matches("[data-previous]")){
        increment = -1
    }

    if(increment == null) {
        return
    }

    const inputs = [...formSteps[currentStep].querySelectorAll("input")]
    const allValid = inputs.every(input => input.reportValidity())

    /* console.log(allValid) */
    if(allValid) {
        currentStep += increment
        
        if(currentStep == 1){
            pbar1.setAttribute("value","100")
            number2.classList.toggle("active", true)
        }

        if(currentStep == 1 && increment < 0){
            pbar2.setAttribute("value","0")
            number3.classList.toggle("active", false)
        }

        if(currentStep == 0 && increment < 0){
            pbar1.setAttribute("value","0")
            number2.classList.toggle("active", false)
        }
        
        if(currentStep == 2){
            pbar2.setAttribute("value","100")
            number3.classList.toggle("active", true)
        }

        showCurrentStep()
    }
    else if(increment < 0){
        currentStep += increment

        if(currentStep == 0){
            pbar1.setAttribute("value","0")
            number2.classList.toggle("active", false)
        }

        if(currentStep == 1){
            pbar2.setAttribute("value","0")
            number3.classList.toggle("active", false)
        }

        showCurrentStep()
    }
   /*  console.log(currentStep) */
})

function showCurrentStep(){
    formSteps.forEach((step, index) => {
        step.classList.toggle("active", index === currentStep)
    })
}