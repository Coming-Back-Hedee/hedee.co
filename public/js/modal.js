

//Vérifie si l'élément match correspond à l'élément node ou un de ses ancêtres
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

      //Fonction pour fermer les messages
      $('.flash-notice-close').click(function(){
         temp = $(this).parent().next()
         $(this).parent().fadeOut()
      })

      // Fonctions pour le menu déroulant
      function openMenu() {
         $('.js-menu-container').addClass('is-open'); // Find element with the class 'js-menu-container' and apply an additional class of 'is-open'
         $('.navbar.fixed-top').fadeToggle('1200');
      }
      function closeMenu() {
         $('.js-menu-container').removeClass('is-open'); // Find element with the class 'js-menu-container' and remove the class 'is-open'
         $('.navbar.fixed-top').fadeToggle('1200');
      }
      // Document Ready
      jQuery(document).ready(function($){ // When everything has finished loading
         $('.js-menu-cookie').addClass('is-open');
         $('.js-menu-button').click(function(){ // When the element with the class 'js-menu-button' is clicked
            openMenu(); // Run the openMenu function
         });
         $('.js-menu-close').click(function(){ // When the element with the class 'js-menu-close' is clicked
            closeMenu(); // Run the closeMenu function
         });
      });
      // Keyboard Accessibility
      jQuery(document).keyup(function(e) { // Listen for keyboard presses
         if (e.keyCode === 27) { // 'Esc' key
            if ($('.js-menu-container').hasClass('is-open')) { // If the menu is open close it
               closeMenu(); // Run the closeMenu function
            }
         }
      });

      	function openInscriptionMenu() {
				$('.js-menu-inscription').addClass('is-open'); // Find element with the class 'js-menu-container' and apply an additional class of 'is-open'
				$('footer').fadeToggle('1200');

			}
			function closeInscriptionMenu() {
				$('.js-menu-inscription').removeClass('is-open'); // Find element with the class 'js-menu-container' and remove the class 'is-open'
				//$('.footer').fadeToggle('1200');
			}

			function openConnectionMenu() {
				$('.js-menu-connection').addClass('is-open'); // Find element with the class 'js-menu-container' and apply an additional class of 'is-open'
				$('footer').fadeToggle('1200');

			}
			function closeConnectionMenu() {
				$('.js-menu-connection').removeClass('is-open'); // Find element with the class 'js-menu-container' and remove the class 'is-open'
				//$('.footer').fadeToggle('1200');
			}

		// Document Ready
			jQuery(document).ready(function($){
				 // When everything has finished loading
				$('.js-menu-client-button').click(function(){
					openConnectionMenu(); // Run the openMenu function
				});
				$('.js-menu-client-close').click(function(){
					 // When the element with the class 'js-menu-close' is clicked
					closeInscriptionMenu(); // Run the closeMenu function
					closeConnectionMenu()
				});
				$('#switch_inscription').click(function(){
					$('.js-menu-inscription').addClass('is-open');
					$('.js-menu-connection').removeClass('is-open');
					openInscriptionMenu();
				});
				$('#switch_connection').click(function(){
					$('.js-menu-connection').addClass('is-open');
					$('.js-menu-inscription').removeClass('is-open');
					openConnectionMenu();
				});
         });
         
		// Keyboard Accessibility
			jQuery(document).keyup(function(e) { // Listen for keyboard presses
				if (e.keyCode === 27) { // 'Esc' key
					if ($('.js-menu-container').hasClass('is-open')) { // If the menu is open close it
						closeMenu(); // Run the closeMenu function
					}
				}
         });

      // Fonction pour fermer le menu insription/connexion
      $('.js-menu-client-close').click(function(){
			window.location.href = "{{ path('accueil') }}"
		})


         // Fonctions pour le menu inscription/connexion
         /*
         	function openInscriptionMenu() {
				$('.js-menu-inscription').addClass('is-open'); // Find element with the class 'js-menu-container' and apply an additional class of 'is-open'
				$('footer').fadeToggle('1200');

			}
			function closeInscriptionMenu() {
				$('.js-menu-inscription').removeClass('is-open'); // Find element with the class 'js-menu-container' and remove the class 'is-open'
				//$('.footer').fadeToggle('1200');
			}

			function openConnectionMenu() {
				$('.js-menu-connection').addClass('is-open'); // Find element with the class 'js-menu-container' and apply an additional class of 'is-open'
				$('footer').fadeToggle('1200');

			}
			function closeConnectionMenu() {
				$('.js-menu-connection').removeClass('is-open'); // Find element with the class 'js-menu-container' and remove the class 'is-open'
				//$('.footer').fadeToggle('1200');
			}

		// Document Ready
			jQuery(document).ready(function($){
				 // When everything has finished loading
				$('.js-menu-client-button').click(function(){
					openConnectionMenu(); // Run the openMenu function
				});
				$('.js-menu-client-close').click(function(){
					 // When the element with the class 'js-menu-close' is clicked
					closeInscriptionMenu(); // Run the closeMenu function
					closeConnectionMenu()
				});
				$('#switch_inscription').click(function(){
					$('.js-menu-inscription').addClass('is-open');
					$('.js-menu-connection').removeClass('is-open');
					openInscriptionMenu();
				});
				$('#switch_connection').click(function(){
					$('.js-menu-connection').addClass('is-open');
					$('.js-menu-inscription').removeClass('is-open');
					openConnectionMenu();
				});
			});
			window.onclick = function(event){console.log(event.target)}
		// Keyboard Accessibility
			jQuery(document).keyup(function(e) { // Listen for keyboard presses
				if (e.keyCode === 27) { // 'Esc' key
					if ($('.js-menu-container').hasClass('is-open')) { // If the menu is open close it
						closeMenu(); // Run the closeMenu function
					}
				}
			});*/
      


