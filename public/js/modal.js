

      function ancestor(node, match)
      {
            
         if(!node)
         {
            return null;
         }
         else if(!node.nodeType || typeof(match) != 'string')
         {
            return node;
         }
         do{

            if(node.nodeName == "BODY"){              
               return null
            }
               
            if(node.getAttribute('id') != undefined  && (match.toLowerCase() == node.getAttribute('id').toLowerCase())){              
               break;
            }
         }
         while(node = node.parentNode);
         
         return node;
      }
      
      function modalConnect(button){
         var modal = document.getElementById("modal");
         var show = document.getElementById("show");
         
         //var form = $("#eligibilite"); 
         //var fond = document.getElementById("fond");
         
         window.onclick = function(event) {
            if (modal.style.display == "none"){
               if (event.target == show  || ancestor(event.target, 'show') != null || event.target == button){        
                  event.preventDefault();  
                  showModal();
               return;
               }
            }
            else{
               if (ancestor(event.target, 'modal') == null){
                     modal.style.display = "none";
                  }
               }      
         }
      }  
   
   // Lorsque l'on modifie la taille du navigateur la taille du fond change
   /*$(window).resize(function () {
      resizeModal()
   });*/

   function showModal(){
      var id = '#modal';
   
      $.ajax({
         type: 'get',
         url: url,
         success: function (data) {
            $(id).html(data);
         }
      });
   
      // On definit la taille de la fenetre modale
      resizeModal();
      
      // Effet de transition     
      $('#fond').fadeIn(1000);   
      $('#fond').fadeTo("slow",0.8);
      
      // Effet de transition   
      $(id).fadeIn(2000);
      
         $('.popup .close').click(function (e) {
            // On désactive le comportement du lien
            e.preventDefault();
         });
   }


	function resizeModal(){
      var modal = $('#modal');
      // On récupère la largeur de l'écran et la hauteur de la page afin de cacher la totalité de l'écran
      var winH = $(document).height();
      var winW = $(window).width();
      
      // le fond aura la taille de l'écran
      $('#fond').css({'width':winW,'height':winH});
      
      // On récupère la hauteur et la largeur de l'écran
      var winH = $(window).height();
      // On met la fenêtre modale au centre de l'écran
      modal.css('top', winH/2 - modal.height()/2);
      modal.css('left', winW/2 - modal.width()/2);
	}


