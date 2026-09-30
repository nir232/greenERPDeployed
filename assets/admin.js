
function openSection(sectionId) {
    const sections = document.querySelectorAll('.section');

    sections.forEach(section => {
        section.style.display = 'none';
    });

    const selected = document.getElementById(sectionId);

    if (selected) {
        selected.style.display = 'block';
    }
}

(function(){
    function stepFieldsValid(step){
        var box=document.getElementById('employeeStep'+step);
        if(!box) return true;
        var fields=box.querySelectorAll('input,select,textarea');
        for(var i=0;i<fields.length;i++){
            if(!fields[i].checkValidity()){
                fields[i].reportValidity();
                return false;
            }
        }
        return true;
    }
    window.setEmployeeStep=function(step){
        for(var i=1;i<=4;i++){
            var el=document.getElementById('employeeStep'+i);
            if(el) el.style.display=(i===step?'block':'none');
        }
    };
    window.employeeNext=function(step){
        if(stepFieldsValid(step)) setEmployeeStep(Math.min(4,step+1));
    };
    window.employeeBack=function(step){
        setEmployeeStep(Math.max(1,step-1));
    };
    document.addEventListener('DOMContentLoaded',function(){
        var form=document.getElementById('employeeForm');
        if(form){
            form.addEventListener('reset',function(){setTimeout(function(){setEmployeeStep(1);},0);});
            setEmployeeStep(1);
        }
    });
})();


const click = document.getElementById("click");
const updates = document.getElementById("updatesFeed");

function hideForms() {
    updates.style.display = "none";
}

function openUpdates() {

    hideForms();

    click.addEventListener('click', (event) => {
        event.stopPropagation();
        updates.style.display = "block";
    });

    document.addEventListener('click', (event) => {

        if (!updates.contains(event.target) && event.target !== click) {
            updates.style.display = "none";
        }

    });

}