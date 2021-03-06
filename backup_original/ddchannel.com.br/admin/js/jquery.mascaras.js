/**
 * @version 1.1
 * The MIT License
 * Copyright (c) 2008 Fabio Miranda Costa http://www.meiocodigo.com
 */
(function(B) {
    var A = (window.orientation != undefined);
    B.extend({
        mascara: {
            rules: {
                "z": /[a-z]/,
                "Z": /[A-Z]/,
                "a": /[a-zA-Z]/,
                "*": /[0-9a-zA-Z]/,
                "@": /[0-9a-zA-ZçÇáàãéèíìóòõúùü]/
            },
            keyRepresentation: {
                8: "backspace",
                9: "tab",
                13: "enter",
                16: "shift",
                17: "control",
                18: "alt",
                27: "esc",
                33: "page up",
                34: "page down",
                35: "end",
                36: "home",
                37: "left",
                38: "up",
                39: "right",
                40: "down",
                45: "insert",
                46: "delete",
                116: "f5",
                224: "command"
            },
            iphoneKeyRepresentation: {
                10: "go",
                127: "delete"
            },
            signals: {
                "+": "",
                "-": "-"
            },
            options: {
                attr: "alt",
                mascara: null,
                type: "fixed",
                maxLength: -1,
                defaultValue: "",
                signal: false,
                autoTab: true,
                fixedChars: "[(),.:/ -]",
                onInvalid: function() {
                },
                onValid: function() {
                },
                onOverflow: function() {
                }
            },
            masks: {
                "phone": {
                    mascara: "(99) 9999-9999"
                },
                "phone-us": {
                    mascara: "(999) 999-9999"
                },
                "cpf": {
                    mascara: "999.999.999-99"
                },
                "cnpj": {
                    mascara: "99.999.999/9999-99"
                },
                "date": {
                    mascara: "39/19/9999"
                },
                "date-us": {
                    mascara: "19/39/9999"
                },
                "cep": {
                    mascara: "99999-999"
                },
                "time": {
                    mascara: "29:59"
                },
                "cc": {
                    mascara: "9999 9999 9999 9999"
                },
                "integer": {
                    mascara: "999.999.999.999",
                    type: "reverse"
                },
                "decimal": {
                    mascara: "99,999.999.999.999",
                    type: "reverse",
                    defaultValue: "000"
                },
                "decimal-us": {
                    mascara: "99.999,999,999,999",
                    type: "reverse",
                    defaultValue: "000"
                },
                "signed-decimal": {
                    mascara: "99,999.999.999.999",
                    type: "reverse",
                    defaultValue: "+000"
                },
                "signed-decimal-us": {
                    mascara: "99,999.999.999.999",
                    type: "reverse",
                    defaultValue: "+000"
                }
            },
            init: function() {
                if (!this.hasInit) {
                    var C = this, D, E = (A) ? this.iphoneKeyRepresentation : this.keyRepresentation;
                    this.ignore = false;
                    for (D = 0; D <= 9; D++) {
                        this.rules[D] = new RegExp("[0-" + D + "]")
                    }
                    this.keyRep = E;
                    this.ignoreKeys = [];
                    B.each(E, function(F) {
                        C.ignoreKeys.push(parseInt(F))
                    });
                    this.hasInit = true
                }
            },
            set: function(G, D) {
                var C = this, E = B(G), F = "maxLength";
                D = D || {};

                this.init();
                return E.each(function() {
                    if (D.attr) {
                        C.options.attr = D.attr
                    }
                    var N = B(this), O = B.extend({}, C.options), M = N.attr(O.attr), H = "", J = C.__getPasteEvent();
                    H = (typeof D == "string") ? D : (M != "") ? M : null;
                    if (H) {
                        O.mascara = H
                    }
                    if (C.masks[H]) {
                        O = B.extend(O, C.masks[H])
                    }
                    if (typeof D == "object" && D.constructor != Array) {
                        O = B.extend(O, D)
                    }
                    if (B.metadata) {
                        O = B.extend(O, N.metadata())
                    }
                    if (O.mascara != null) {
                        if (N.data("mascara")) {
                            C.unset(N)
                        }
                        var I = O.defaultValue, K = (O.type == "reverse"), L = new RegExp(O.fixedChars, "g");
                        if (O.maxLength == -1) {
                            O.maxLength = N.attr(F)
                        }
                        O = B.extend({}, O, {
                            fixedCharsReg: new RegExp(O.fixedChars),
                            fixedCharsRegG: L,
                            maskArray: O.mascara.split(""),
                            maskNonFixedCharsArray: O.mascara.replace(L, "").split("")
                        });
                        if (K) {
                            N.css("text-align", "right")
                        }
                        if (N.val() != "") {
                            N.val(C.string(N.val(), O))
                        } else {
                            if (I != "") {
                                N.val(C.string(I, O))
                            }
                        }
                        N.data("mascara", O);
                        N.removeAttr(F);
                        N.bind("keydown", {
                            func: C._keyDown,
                            thisObj: C
                        }, C._onMask).bind("keyup", {
                            func: C._keyUp,
                            thisObj: C
                        }, C._onMask).bind("keypress", {
                            func: C._keyPress,
                            thisObj: C
                        }, C._onMask).bind("focus", C._onFocus).bind("blur", C._onBlur).bind("change", C._onChange).bind(J, {
                            func: C._paste,
                            thisObj: C
                        }, C._delayedOnMask)
                    }
                })
            },
            unset: function(D) {
                var C = B(D), E = this;
                return C.each(function() {
                    var H = B(this);
                    if (H.data("mascara")) {
                        var F = H.data("mascara").maxLength, G = E.__getPasteEvent();
                        if (F != -1) {
                            H.attr("maxLength", F)
                        }
                        H.unbind("keydown", E._onMask).unbind("keypress", E._onMask).unbind("keyup", E._onMask).unbind(G, E._delayedOnMask).unbind("focus", E._onFocus).unbind("blur", E._onBlur).unbind("change", E._onChange).removeData("mascara")
                    }
                })
            },
            string: function(H, D) {
                this.init();
                var G = {};

                if (typeof H != "string") {
                    H = String(H)
                }
                switch (typeof D) {
                    case"string":
                        if (this.masks[D]) {
                            G = B.extend(G, this.masks[D])
                        } else {
                            G.mascara = D
                        }
                        break;
                    case"object":
                        G = D
                }
                if (!G.fixedChars) {
                    G.fixedChars = this.options.fixedChars
                }
                var C = new RegExp(G.fixedChars), E = new RegExp(G.fixedChars, "g");
                if ((G.type == "reverse") && G.defaultValue) {
                    if (typeof this.signals[G.defaultValue.charAt(0)] != "undefined") {
                        var F = H.charAt(0);
                        G.signal = (typeof this.signals[F] != "undefined") ? this.signals[F] : this.signals[G.defaultValue.charAt(0)];
                        G.defaultValue = G.defaultValue.substring(1)
                    }
                }
                return this.__maskArray(H.split(""), G.mascara.replace(E, "").split(""), G.mascara.split(""), G.type, G.maxLength, G.defaultValue, C, G.signal)
            },
            _onFocus: function(E) {
                var D = B(this), C = D.data("mascara");
                C.inputFocusValue = D.val();
                C.changed = false
            },
            _onBlur: function(E) {
                var D = B(this), C = D.data("mascara");
                if (C.inputFocusValue != D.val() && C.type == "reverse" && !C.changed) {
                    D.trigger("change")
                }
            },
            _onChange: function(C) {
                B(this).data("mascara").changed = true
            },
            _onMask: function(C) {
                var E = C.data.thisObj, D = {};

                D._this = C.target;
                D.$this = B(D._this);
                if (D.$this.attr("readonly")) {
                    return true
                }
                D.data = D.$this.data("mascara");
                if (D.data.type == "infinite") {
                    D.data.type = "repeat"
                }
                D[D.data.type] = true;
                D.value = D.$this.val();
                D.nKey = E.__getKeyNumber(C);
                D.range = E.__getRange(D._this);
                D.valueArray = D.value.split("");
                return C.data.func.call(E, C, D)
            },
            _delayedOnMask: function(C) {
                C.type = "paste";
                setTimeout(function() {
                    C.data.thisObj._onMask(C)
                }, 1)
            },
            _keyDown: function(D, E) {
                this.ignore = B.inArray(E.nKey, this.ignoreKeys) > -1 || D.ctrlKey || D.metaKey || D.altKey;
                if (this.ignore) {
                    var C = this.keyRep[E.nKey];
                    E.data.onValid.call(E._this, C ? C : "", E.nKey)
                }
                return A ? this._keyPress(D, E) : true
            },
            _keyUp: function(C, D) {
                if (D.nKey == 9 || D.nKey == 16) {
                    return true
                }
                return this._paste(C, D)
            },
            _paste: function(E, F) {
                if (F.reverse) {
                    this.__changeSignal(E.type, F)
                }
                var D = this.__maskArray(F.valueArray, F.data.maskNonFixedCharsArray, F.data.maskArray, F.data.type, F.data.maxLength, F.data.defaultValue, F.data.fixedCharsReg, F.data.signal);
                F.$this.val(D);
                if (!F.reverse && F.data.defaultValue.length && (F.range.start == F.range.end)) {
                    this.__setRange(F._this, F.range.start, F.range.end)
                }
                if ((B.browser.msie || B.browser.safari) && !F.reverse) {
                    this.__setRange(F._this, F.range.start, F.range.end)
                }
                if (this.ignore) {
                    return true
                }
                if (F.data.autoTab && ((F.$this.val().length >= F.data.maskArray.length && !F.repeat) || (F.data.maxLength != -1 && F.valueArray.length >= F.data.maxLength && F.repeat))) {
                    var C = this.__getNextInput(F._this, F.data.autoTab);
                    if (C) {
                        F.$this.trigger("blur");
                        C.focus().select()
                    }
                }
                return true
            },
            _keyPress: function(J, C) {
                if (this.ignore) {
                    return true
                }
                if (C.reverse) {
                    this.__changeSignal(J.type, C)
                }
                var K = String.fromCharCode(C.nKey), M = C.range.start, G = C.value, E = C.data.maskArray;
                if (C.reverse) {
                    var F = G.substr(0, M), I = G.substr(C.range.end, G.length);
                    G = F + K + I;
                    if (C.data.signal && (M - C.data.signal.length > 0)) {
                        M -= C.data.signal.length
                    }
                }
                var L = G.replace(C.data.fixedCharsRegG, "").split(""), D = this.__extraPositionsTill(M, E, C.data.fixedCharsReg);
                C.rsEp = M + D;
                if (C.repeat) {
                    C.rsEp = 0
                }
                if (!this.rules[E[C.rsEp]] || (C.data.maxLength != -1 && L.length >= C.data.maxLength && C.repeat)) {
                    C.data.onOverflow.call(C._this, K, C.nKey);
                    return false
                } else {
                    if (!this.rules[E[C.rsEp]].test(K) && K != E[M]) {
                        C.data.onInvalid.call(C._this, K, C.nKey);
                        return false
                    } else {
                        C.data.onValid.call(C._this, K, C.nKey)
                    }
                }
                var H = this.__maskArray(L, C.data.maskNonFixedCharsArray, E, C.data.type, C.data.maxLength, C.data.defaultValue, C.data.fixedCharsReg, C.data.signal, D);
                C.$this.val(H);
                return(C.reverse) ? this._keyPressReverse(J, C) : (C.fixed) ? this._keyPressFixed(J, C) : true
            },
            _keyPressFixed: function(C, D) {
                if (D.range.start == D.range.end) {
                    if ((D.rsEp == 0 && D.value.length == 0) || D.rsEp < D.value.length) {
                        this.__setRange(D._this, D.rsEp, D.rsEp + 1)
                    }
                } else {
                    this.__setRange(D._this, D.range.start, D.range.end)
                }
                return true
            },
            _keyPressReverse: function(C, D) {
                if (B.browser.msie && ((D.rangeStart == 0 && D.range.end == 0) || D.rangeStart != D.range.end)) {
                    this.__setRange(D._this, D.value.length)
                }
                return false
            },
            __changeSignal: function(D, E) {
                if (E.data.signal !== false) {
                    var C = (D == "paste") ? E.value.charAt(0) : String.fromCharCode(E.nKey);
                    if (this.signals && (typeof this.signals[C] != "undefined")) {
                        E.data.signal = this.signals[C]
                    }
                }
            },
            __getPasteEvent: function() {
                return(B.browser.opera || (B.browser.mozilla && parseFloat(B.browser.version.substr(0, 3)) < 1.9)) ? "input" : "paste"
            },
            __getKeyNumber: function(C) {
                return(C.charCode || C.keyCode || C.which)
            },
            __maskArray: function(K, F, E, H, C, I, L, J, D) {
                if (H == "reverse") {
                    K.reverse()
                }
                K = this.__removeInvalidChars(K, F, H == "repeat" || H == "infinite");
                if (I) {
                    K = this.__applyDefaultValue.call(K, I)
                }
                K = this.__applyMask(K, E, D, L);
                switch (H) {
                    case"reverse":
                        K.reverse();
                        return(J || "") + K.join("").substring(K.length - E.length);
                    case"infinite":
                    case"repeat":
                        var G = K.join("");
                        return(C != -1 && K.length >= C) ? G.substring(0, C) : G;
                    default:
                        return K.join("").substring(0, E.length)
                }
                return""
            },
            __applyDefaultValue: function(E) {
                var C = E.length, D = this.length, F;
                for (F = D - 1; F >= 0; F--) {
                    if (this[F] == E.charAt(0)) {
                        this.pop()
                    } else {
                        break
                    }
                }
                for (F = 0; F < C; F++) {
                    if (!this[F]) {
                        this[F] = E.charAt(F)
                    }
                }
                return this
            },
            __removeInvalidChars: function(F, E, C) {
                for (var D = 0, G = 0; D < F.length; D++) {
                    if (E[G] && this.rules[E[G]] && !this.rules[E[G]].test(F[D])) {
                        F.splice(D, 1);
                        if (!C) {
                            G--
                        }
                        D--
                    }
                    if (!C) {
                        G++
                    }
                }
                return F
            },
            __applyMask: function(F, D, G, C) {
                if (typeof G == "undefined") {
                    G = 0
                }
                for (var E = 0; E < F.length + G; E++) {
                    if (D[E] && C.test(D[E])) {
                        F.splice(E, 0, D[E])
                    }
                }
                return F
            },
            __extraPositionsTill: function(F, D, C) {
                var E = 0;
                while (C.test(D[F])) {
                    F++;
                    E++
                }
                return E
            },
            __getNextInput: function(L, E) {
                var H = L.form.elements, G = B.inArray(L, H) + 1, D = null, I;
                for (I = G; I < H.length; I++) {
                    D = B(H[I]);
                    if (this.__isNextInput(D, E)) {
                        return D
                    }
                }
                var C = document.forms, F = B.inArray(L.form, C) + 1, K, J = null;
                for (K = F; K < C.length; K++) {
                    J = C[K].elements;
                    for (I = 0; I < J.length; I++) {
                        D = B(J[I]);
                        if (this.__isNextInput(D, E)) {
                            return D
                        }
                    }
                }
                return null
            },
            __isNextInput: function(D, C) {
                return D && D.attr("type") != "hidden" && D.get(0).tagName.toLowerCase() != "fieldset" && (C === true || (typeof C == "string" && D.is(C)))
            },
            __setRange: function(E, F, C) {
                if (typeof C == "undefined") {
                    C = F
                }
                if (E.setSelectionRange) {
                    E.setSelectionRange(F, C)
                } else {
                    var D = E.createTextRange();
                    D.collapse();
                    D.moveStart("character", F);
                    D.moveEnd("character", C - F);
                    D.select()
                }
            },
            __getRange: function(D) {
                if (!B.browser.msie) {
                    return{
                        start: D.selectionStart,
                        end: D.selectionEnd
                    }
                }
                var E = {
                    start: 0,
                    end: 0
                }, C = document.selection.createRange();
                E.start = 0 - C.duplicate().moveStart("character", -100000);
                E.end = E.start + C.text.length;
                return E
            },
            unmaskedVal: function(C) {
                return B(C).val().replace(B.mascara.fixedCharsRegG, "")
            }
        }
    });
    B.fn.extend({
        setMask: function(C) {
            return B.mascara.set(this, C)
        },
        unsetMask: function() {
            return B.mascara.unset(this)
        },
        unmaskedVal: function() {
            return B.mascara.unmaskedVal(this[0])
        }
    })
})(jQuery)