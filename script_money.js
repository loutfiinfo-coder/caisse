/*
'***********************************************************
' ConvNumberLetter - Conversion d'un nombre en lettres (Français)
' Compatible : JavaScript standard (WAMP 2.5, tous navigateurs)
'
' Devise = 0  aucune
'        = 1  Dirham (Maroc)
'        = 2  Dollar
' Langue = 0  Français (France/Maroc)
'        = 1  Belgique (septante, nonante)
'        = 2  Suisse   (septante, huitante, nonante)
'
' Limites : 999 999 999 999 999  ou  9 999 999 999 999,99
' Si plus de 2 décimales, troncature à 2 décimales.
'
' Règles orthographiques appliquées :
'  - quatre-vingt / quatre-vingts (s uniquement si non suivi d'un autre numéral)
'  - cent / cents (s uniquement si multiplié ET non suivi d'un autre numéral)
'  - dix-sept, dix-huit, dix-neuf (traits d'union)
'  - liaison "et" pour 21, 31, 41, 51, 61, 71
'  - traits d'union entre dizaine et unité (rectifications de 1990)
'  - "zéro" devant les centimes de 01 à 09
'  - "de Dirhams" pour les multiples exacts de million/milliard/billion
'  - support des nombres négatifs (préfixe "moins")
'  - première lettre en majuscule
'***********************************************************/
function ConvNumberLetter_fr(Nombre, bCheckFloat) {
    if (Nombre === null || Nombre === undefined) return "" ;
    if (isNaN(parseFloat(Nombre))) return "" ;

    var strNombre = new String(Nombre) ;
    var bNegatif = false ;

    if (parseFloat(Nombre) < 0) {
        bNegatif = true ;
        strNombre = strNombre.replace("-", "") ;
    }

    var TabNombre = strNombre.split(".") ;
    if (TabNombre.length > 2 || TabNombre.length <= 0) return "" ;

    var nEnt = parseInt(TabNombre[0], 10) ;
    if (isNaN(nEnt)) nEnt = 0 ;

    var nDec = 0 ;
    var bHasDec = false ;
    if (TabNombre.length == 2) {
        bHasDec = true ;
        var sDec = TabNombre[1] ;
        if (sDec.length == 1) sDec = sDec + "0" ;
        if (sDec.length > 2) sDec = sDec.substring(0, 2) ;
        nDec = parseInt(sDec, 10) ;
        if (isNaN(nDec)) nDec = 0 ;
    }

    var strLetter = ConvNumberLetter(nEnt, 1, 0) ;

    if (bHasDec && nDec > 0) {
        var letterDec = ConvNumberLetter(nDec, 0, 0) ;
        if (nDec >= 1 && nDec <= 9) {
            letterDec = "zéro " + letterDec ;
        }
        strLetter = strLetter + " et " + letterDec + " Cts" ;
    }

    if (bNegatif) {
        strLetter = "moins " + strLetter ;
    }

    strLetter = strLetter.replace(/\s+/g, " ").replace(/^\s+|\s+$/g, "") ;

    if (strLetter.length > 0) {
        strLetter = strLetter.charAt(0).toUpperCase() + strLetter.substring(1) ;
    }

    return strLetter ;
}

function ConvNumberLetter(Nombre, Devise, Langue) {
    var dblEnt ;
    var strDev = new String() ;
    var strDevPrefix = "" ;

    Nombre = Math.abs(Nombre) ;
    dblEnt = parseInt(Nombre, 10) ;

    if (dblEnt > 999999999999999) {
        return "#TropGrand" ;
    }

    // Multiple exact de million / milliard / billion ?
    var bMultipleExact = false ;
    if (dblEnt >= 1000000 && (dblEnt % 1000000) === 0) {
        bMultipleExact = true ;
    }

    switch (Devise) {
        case 0 :
            break ;
        case 1 :
            strDev = " Dirham" ;
            break ;
        case 2 :
            strDev = " Dollar" ;
            break ;
    }

    if (dblEnt > 1 && Devise != 0) strDev = strDev + "s" ;

    if (bMultipleExact && Devise != 0) {
        strDevPrefix = " de" ;
    }

    if (dblEnt == 0) {
        if (Devise != 0) return "zéro" + strDev ;
        return "zéro" ;
    }

    return ConvNumEnt(dblEnt, Langue) + strDevPrefix + strDev ;
}

function ConvNumEnt(Nombre, Langue) {
    var iTmp, dblReste ;
    var StrTmp = new String() ;
    var NumEnt = new String() ;

    iTmp = Nombre - (parseInt(Nombre / 1000) * 1000) ;
    NumEnt = ConvNumCent(parseInt(iTmp), Langue, false) ;

    dblReste = parseInt(Nombre / 1000) ;
    iTmp = dblReste - (parseInt(dblReste / 1000) * 1000) ;
    StrTmp = ConvNumCent(parseInt(iTmp), Langue, true) ;
    switch (iTmp) {
        case 0 :
            StrTmp = "" ;
            break ;
        case 1 :
            StrTmp = "mille " ;
            break ;
        default :
            StrTmp = StrTmp + " mille " ;
    }
    NumEnt = StrTmp + NumEnt ;

    dblReste = parseInt(dblReste / 1000) ;
    iTmp = dblReste - (parseInt(dblReste / 1000) * 1000) ;
    StrTmp = ConvNumCent(parseInt(iTmp), Langue, false) ;
    switch (iTmp) {
        case 0 :
            StrTmp = "" ;
            break ;
        case 1 :
            StrTmp = StrTmp + " million " ;
            break ;
        default :
            StrTmp = StrTmp + " millions " ;
    }
    NumEnt = StrTmp + NumEnt ;

    dblReste = parseInt(dblReste / 1000) ;
    iTmp = dblReste - (parseInt(dblReste / 1000) * 1000) ;
    StrTmp = ConvNumCent(parseInt(iTmp), Langue, false) ;
    switch (iTmp) {
        case 0 :
            StrTmp = "" ;
            break ;
        case 1 :
            StrTmp = StrTmp + " milliard " ;
            break ;
        default :
            StrTmp = StrTmp + " milliards " ;
    }
    NumEnt = StrTmp + NumEnt ;

    dblReste = parseInt(dblReste / 1000) ;
    iTmp = dblReste - (parseInt(dblReste / 1000) * 1000) ;
    StrTmp = ConvNumCent(parseInt(iTmp), Langue, false) ;
    switch (iTmp) {
        case 0 :
            StrTmp = "" ;
            break ;
        case 1 :
            StrTmp = StrTmp + " billion " ;
            break ;
        default :
            StrTmp = StrTmp + " billions " ;
    }
    NumEnt = StrTmp + NumEnt ;

    return NumEnt ;
}

function ConvNumDizaine(Nombre, Langue, bSuiviNumeral) {
    var TabUnit, TabDiz ;
    var byUnit, byDiz ;
    var strLiaison = new String() ;

    TabUnit = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept",
        "huit", "neuf", "dix", "onze", "douze", "treize", "quatorze", "quinze",
        "seize", "dix-sept", "dix-huit", "dix-neuf"] ;
    TabDiz = ["", "", "vingt", "trente", "quarante", "cinquante",
        "soixante", "soixante", "quatre-vingt", "quatre-vingt"] ;

    if (Langue == 1) {
        TabDiz[7] = "septante" ;
        TabDiz[9] = "nonante" ;
    } else if (Langue == 2) {
        TabDiz[7] = "septante" ;
        TabDiz[8] = "huitante" ;
        TabDiz[9] = "nonante" ;
    }

    byDiz = parseInt(Nombre / 10) ;
    byUnit = Nombre - (byDiz * 10) ;
    strLiaison = "-" ;
    if (byUnit == 1) strLiaison = " et " ;

    switch (byDiz) {
        case 0 :
            strLiaison = "" ;
            break ;
        case 1 :
            byUnit = byUnit + 10 ;
            strLiaison = "" ;
            break ;
        case 7 :
            if (Langue == 0) byUnit = byUnit + 10 ;
            break ;
        case 8 :
            strLiaison = "-" ;
            break ;
        case 9 :
            if (Langue == 0) {
                byUnit = byUnit + 10 ;
                strLiaison = "-" ;
            }
            break ;
    }

    var NumDizaine = TabDiz[byDiz] ;
    if (byDiz == 8 && Langue != 2 && byUnit == 0 && !bSuiviNumeral) {
        NumDizaine = NumDizaine + "s" ;
    }
    if (TabUnit[byUnit] != "") {
        NumDizaine = NumDizaine + strLiaison + TabUnit[byUnit] ;
    }
    return NumDizaine ;
}

function ConvNumCent(Nombre, Langue, bSuiviNumeral) {
    var TabUnit ;
    var byCent, byReste ;
    var strReste = new String() ;
    var NumCent ;

    TabUnit = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept",
        "huit", "neuf", "dix"] ;

    byCent = parseInt(Nombre / 100) ;
    byReste = Nombre - (byCent * 100) ;
    strReste = ConvNumDizaine(byReste, Langue, bSuiviNumeral) ;

    switch (byCent) {
        case 0 :
            NumCent = strReste ;
            break ;
        case 1 :
            if (byReste == 0) NumCent = "cent" ;
            else NumCent = "cent " + strReste ;
            break ;
        default :
            if (byReste == 0) {
                if (bSuiviNumeral) NumCent = TabUnit[byCent] + " cent" ;
                else NumCent = TabUnit[byCent] + " cents" ;
            } else {
                NumCent = TabUnit[byCent] + " cent " + strReste ;
            }
    }
    return NumCent ;
}
