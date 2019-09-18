function ucfirst(str) {
    var word = ""
    var text = str;
    if (text != ""){
        var first = text[0].toUpperCase();
        var rest = text.substring(1, text.length);
        word = first + rest;
    }
    
    return word;
}; /***** fin fonctionucfirst *****/ 

function verifierCaracteres(event) {	 		
                        
    var caracteres = [48, 49, 50, 51, 52 ,53, 54, 55, 56, 57]
    if(caracteres.indexOf(event.keyCode) < 0){
        event.preventDefault;
        return false;
    }
    else{
        return true;
    }	
} /***** fin fonction verifierCaracteres *****/ 

function verifierCaracteres1(event) {
    var caracteres = [44, 46, 48, 49, 50, 51, 52 ,53, 54, 55, 56, 57]
    if(caracteres.indexOf(event.keyCode) < 0){
        event.preventDefault;
        return false;
    }
    else{
        return true;
    }
} /***** fin fonction verifierCaracteres1 *****/ 

function attachDatepicker(){   
    $("#datepicker" ).datepicker({ 
        minDate: -30, maxDate: 0, 
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'dd-mm-yy' 
    }); 
} /***** fin fonction attachDatepicker *****/

var redirect = function(){
    Array.from(document.getElementsByClassName("replace_btn")).forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault()
            document.getElementById("menunext").click()
        })
    })
}
 /***** fin fonction redirect *****/ 