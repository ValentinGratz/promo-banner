jQuery(document).ready(function($){
    var myOptions = {
        // Vous pouvez déclarer une couleur par défaut ici,
        // ou dans l'attribut data-default-color sur l'entrée
        defaultColor: false,
        // un rappel à déclencher chaque fois que la couleur change pour une couleur valide
        change: function(event, ui){},
        // un rappel à déclencher lorsque l'entrée est vidée ou une couleur invalide
        clear: function() {},
        // masquer les contrôles du color picker au chargement
        hide: true,
        // afficher un groupe de couleurs communes sous le carré
        // ou, fournissez un tableau de couleurs pour personnaliser davantage
        palettes: true
    };
    $('.my-color-field').wpColorPicker(myOptions);
});
