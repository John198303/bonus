{"version":3,"sources":["logic.js"],"names":["BX","namespace","Tasks","Component","TaskDetailPartsTemplateSelector","Util","Widget","extend","sys","code","methods","construct","this","callConstruct","bind","control","onPopupOpen","menu","menuItems","e","window","event","target","currentTarget","getMenuItems","then","items","showMenu","bindElement","createMenu","popupWindow","show","addClass","p","Promise","option","length","fulfill","ajax","runComponentAction","mode","data","select","order","ID","filter","ZOMBIE","response","makeItemsFromResult","reject","commonUrl","url","indexOf","href","location","path","pathname","_query","split","query","substr","params","Object","keys","forEach","i","value","undefined","join","each","item","push","parseInt","TITLE","URL","onClick","close","text","util","htmlspecialchars","title","onclick","delimiter","message","menuId","id","popupMenu","PopupMenuWindow","offsetLeft","closeByEsc","angle","position","events","onPopupClose","itemLayout","layout","dataset","sliderIgnoreAutobinding","removeClass","call"],"mappings":"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","file":"logic.map.js"}