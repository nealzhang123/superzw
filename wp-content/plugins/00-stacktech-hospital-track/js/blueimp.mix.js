!function(e) {
    "use strict";
    var n = function(e, t) {
        var r = /[^\w\-\.:]/.test(e) ? new Function(n.arg + ",tmpl", "var _e=tmpl.encode" + n.helper + ",_s='" + e.replace(n.regexp, n.func) + "';return _s;") : n.cache[e] = n.cache[e] || n(n.load(e));
        return t ? r(t, n) : function(e) {
            return r(e, n)
        }
    };
    n.cache = {}, n.load = function(e) {
        return document.getElementById(e).innerHTML
    }, n.regexp = /([\s'\\])(?!(?:[^{]|\{(?!%))*%\})|(?:\{%(=|#)([\s\S]+?)%\})|(\{%)|(%\})/g, n.func = function(e, n, t, r, c, u) {
        return n ? {
            "\n": "\\n",
            "\r": "\\r",
            "	": "\\t",
            " ": " "
        }[n] || "\\" + n : t ? "=" === t ? "'+_e(" + r + ")+'" : "'+(" + r + "==null?'':" + r + ")+'" : c ? "';" : u ? "_s+='" : void 0
    }, n.encReg = /[<>&"'\x00]/g, n.encMap = {
        "<": "&lt;",
        ">": "&gt;",
        "&": "&amp;",
        '"': "&quot;",
        "'": "&#39;"
    }, n.encode = function(e) {
        return (null == e ? "" : "" + e).replace(n.encReg, function(e) {
            return n.encMap[e] || ""
        })
    }, n.arg = "o", n.helper = ",print=function(s,e){_s+=e?(s==null?'':s):_e(s);},include=function(s,d){_s+=tmpl(s,d);}", "function" == typeof define && define.amd ? define(function() {
        return n
    }) : "object" == typeof module && module.exports ? module.exports = n : e.tmpl = n
}(this);
//# sourceMappingURL=tmpl.min.js.map
//



!function(e) {
    "use strict";
    var t = function(e, i, a) {
            var o, r, n = document.createElement("img");
            if (n.onerror = i, n.onload = function() {
                    !r || a && a.noRevoke || t.revokeObjectURL(r), i && i(t.scale(n, a))
                }, t.isInstanceOf("Blob", e) || t.isInstanceOf("File", e)) o = r = t.createObjectURL(e), n._type = e.type;
            else {
                if ("string" != typeof e) return !1;
                o = e, a && a.crossOrigin && (n.crossOrigin = a.crossOrigin)
            }
            return o ? (n.src = o, n) : t.readFile(e, function(e) {
                var t = e.target;
                t && t.result ? n.src = t.result : i && i(e)
            })
        },
        i = window.createObjectURL && window || window.URL && URL.revokeObjectURL && URL || window.webkitURL && webkitURL;
    t.isInstanceOf = function(e, t) {
        return Object.prototype.toString.call(t) === "[object " + e + "]"
    }, t.transformCoordinates = function() {}, t.getTransformedOptions = function(e, t) {
        var i, a, o, r, n = t.aspectRatio;
        if (!n) return t;
        i = {};
        for (a in t) t.hasOwnProperty(a) && (i[a] = t[a]);
        return i.crop = !0, o = e.naturalWidth || e.width, r = e.naturalHeight || e.height, o / r > n ? (i.maxWidth = r * n, i.maxHeight = r) : (i.maxWidth = o, i.maxHeight = o / n), i
    }, t.renderImageToCanvas = function(e, t, i, a, o, r, n, s, d, l) {
        return e.getContext("2d").drawImage(t, i, a, o, r, n, s, d, l), e
    }, t.hasCanvasOption = function(e) {
        return e.canvas || e.crop || e.aspectRatio
    }, t.scale = function(e, i) {
        i = i || {};
        var a, o, r, n, s, d, l, c, u, g = document.createElement("canvas"),
            f = e.getContext || t.hasCanvasOption(i) && g.getContext,
            h = e.naturalWidth || e.width,
            m = e.naturalHeight || e.height,
            p = h,
            S = m,
            b = function() {
                var e = Math.max((r || p) / p, (n || S) / S);
                e > 1 && (p *= e, S *= e)
            },
            x = function() {
                var e = Math.min((a || p) / p, (o || S) / S);
                1 > e && (p *= e, S *= e)
            };
        return f && (i = t.getTransformedOptions(e, i), l = i.left || 0, c = i.top || 0, i.sourceWidth ? (s = i.sourceWidth, void 0 !== i.right && void 0 === i.left && (l = h - s - i.right)) : s = h - l - (i.right || 0), i.sourceHeight ? (d = i.sourceHeight, void 0 !== i.bottom && void 0 === i.top && (c = m - d - i.bottom)) : d = m - c - (i.bottom || 0), p = s, S = d), a = i.maxWidth, o = i.maxHeight, r = i.minWidth, n = i.minHeight, f && a && o && i.crop ? (p = a, S = o, u = s / d - a / o, 0 > u ? (d = o * s / a, void 0 === i.top && void 0 === i.bottom && (c = (m - d) / 2)) : u > 0 && (s = a * d / o, void 0 === i.left && void 0 === i.right && (l = (h - s) / 2))) : ((i.contain || i.cover) && (r = a = a || r, n = o = o || n), i.cover ? (x(), b()) : (b(), x())), f ? (g.width = p, g.height = S, t.transformCoordinates(g, i), t.renderImageToCanvas(g, e, l, c, s, d, 0, 0, p, S)) : (e.width = p, e.height = S, e)
    }, t.createObjectURL = function(e) {
        return i ? i.createObjectURL(e) : !1
    }, t.revokeObjectURL = function(e) {
        return i ? i.revokeObjectURL(e) : !1
    }, t.readFile = function(e, t, i) {
        if (window.FileReader) {
            var a = new FileReader;
            if (a.onload = a.onerror = t, i = i || "readAsDataURL", a[i]) return a[i](e), a
        }
        return !1
    }, "function" == typeof define && define.amd ? define(function() {
        return t
    }) : "object" == typeof module && module.exports ? module.exports = t : e.loadImage = t
}(window),
function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["load-image"], e) : e("object" == typeof module && module.exports ? require("./load-image") : window.loadImage)
}(function(e) {
    "use strict";
    if (window.navigator && window.navigator.platform && /iP(hone|od|ad)/.test(window.navigator.platform)) {
        var t = e.renderImageToCanvas;
        e.detectSubsampling = function(e) {
            var t, i;
            return e.width * e.height > 1048576 ? (t = document.createElement("canvas"), t.width = t.height = 1, i = t.getContext("2d"), i.drawImage(e, -e.width + 1, 0), 0 === i.getImageData(0, 0, 1, 1).data[3]) : !1
        }, e.detectVerticalSquash = function(e, t) {
            var i, a, o, r, n, s = e.naturalHeight || e.height,
                d = document.createElement("canvas"),
                l = d.getContext("2d");
            for (t && (s /= 2), d.width = 1, d.height = s, l.drawImage(e, 0, 0), i = l.getImageData(0, 0, 1, s).data, a = 0, o = s, r = s; r > a;) n = i[4 * (r - 1) + 3], 0 === n ? o = r : a = r, r = o + a >> 1;
            return r / s || 1
        }, e.renderImageToCanvas = function(i, a, o, r, n, s, d, l, c, u) {
            if ("image/jpeg" === a._type) {
                var g, f, h, m, p = i.getContext("2d"),
                    S = document.createElement("canvas"),
                    b = 1024,
                    x = S.getContext("2d");
                if (S.width = b, S.height = b, p.save(), g = e.detectSubsampling(a), g && (o /= 2, r /= 2, n /= 2, s /= 2), f = e.detectVerticalSquash(a, g), g || 1 !== f) {
                    for (r *= f, c = Math.ceil(b * c / n), u = Math.ceil(b * u / s / f), l = 0, m = 0; s > m;) {
                        for (d = 0, h = 0; n > h;) x.clearRect(0, 0, b, b), x.drawImage(a, o, r, n, s, -h, -m, n, s), p.drawImage(S, 0, 0, b, b, d, l, c, u), h += b, d += c;
                        m += b, l += u
                    }
                    return p.restore(), i
                }
            }
            return t(i, a, o, r, n, s, d, l, c, u)
        }
    }
}),
function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["load-image"], e) : e("object" == typeof module && module.exports ? require("./load-image") : window.loadImage)
}(function(e) {
    "use strict";
    var t = e.hasCanvasOption,
        i = e.transformCoordinates,
        a = e.getTransformedOptions;
    e.hasCanvasOption = function(i) {
        return t.call(e, i) || i.orientation
    }, e.transformCoordinates = function(t, a) {
        i.call(e, t, a);
        var o = t.getContext("2d"),
            r = t.width,
            n = t.height,
            s = a.orientation;
        if (s && !(s > 8)) switch (s > 4 && (t.width = n, t.height = r), s) {
            case 2:
                o.translate(r, 0), o.scale(-1, 1);
                break;
            case 3:
                o.translate(r, n), o.rotate(Math.PI);
                break;
            case 4:
                o.translate(0, n), o.scale(1, -1);
                break;
            case 5:
                o.rotate(.5 * Math.PI), o.scale(1, -1);
                break;
            case 6:
                o.rotate(.5 * Math.PI), o.translate(0, -n);
                break;
            case 7:
                o.rotate(.5 * Math.PI), o.translate(r, -n), o.scale(-1, 1);
                break;
            case 8:
                o.rotate(-.5 * Math.PI), o.translate(-r, 0)
        }
    }, e.getTransformedOptions = function(t, i) {
        var o, r, n = a.call(e, t, i),
            s = n.orientation;
        if (!s || s > 8 || 1 === s) return n;
        o = {};
        for (r in n) n.hasOwnProperty(r) && (o[r] = n[r]);
        switch (n.orientation) {
            case 2:
                o.left = n.right, o.right = n.left;
                break;
            case 3:
                o.left = n.right, o.top = n.bottom, o.right = n.left, o.bottom = n.top;
                break;
            case 4:
                o.top = n.bottom, o.bottom = n.top;
                break;
            case 5:
                o.left = n.top, o.top = n.left, o.right = n.bottom, o.bottom = n.right;
                break;
            case 6:
                o.left = n.top, o.top = n.right, o.right = n.bottom, o.bottom = n.left;
                break;
            case 7:
                o.left = n.bottom, o.top = n.right, o.right = n.top, o.bottom = n.left;
                break;
            case 8:
                o.left = n.bottom, o.top = n.left, o.right = n.top, o.bottom = n.right
        }
        return n.orientation > 4 && (o.maxWidth = n.maxHeight, o.maxHeight = n.maxWidth, o.minWidth = n.minHeight, o.minHeight = n.minWidth, o.sourceWidth = n.sourceHeight, o.sourceHeight = n.sourceWidth), o
    }
}),
function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["load-image"], e) : e("object" == typeof module && module.exports ? require("./load-image") : window.loadImage)
}(function(e) {
    "use strict";
    var t = window.Blob && (Blob.prototype.slice || Blob.prototype.webkitSlice || Blob.prototype.mozSlice);
    e.blobSlice = t && function() {
        var e = this.slice || this.webkitSlice || this.mozSlice;
        return e.apply(this, arguments)
    }, e.metaDataParsers = {
        jpeg: {
            65505: []
        }
    }, e.parseMetaData = function(t, i, a) {
        a = a || {};
        var o = this,
            r = a.maxMetaDataSize || 262144,
            n = {},
            s = !(window.DataView && t && t.size >= 12 && "image/jpeg" === t.type && e.blobSlice);
        (s || !e.readFile(e.blobSlice.call(t, 0, r), function(t) {
            if (t.target.error) return console.log(t.target.error), void i(n);
            var r, s, d, l, c = t.target.result,
                u = new DataView(c),
                g = 2,
                f = u.byteLength - 4,
                h = g;
            if (65496 === u.getUint16(0)) {
                for (; f > g && (r = u.getUint16(g), r >= 65504 && 65519 >= r || 65534 === r);) {
                    if (s = u.getUint16(g + 2) + 2, g + s > u.byteLength) {
                        console.log("Invalid meta data: Invalid segment size.");
                        break
                    }
                    if (d = e.metaDataParsers.jpeg[r])
                        for (l = 0; l < d.length; l += 1) d[l].call(o, u, g, s, n, a);
                    g += s, h = g
                }!a.disableImageHead && h > 6 && (c.slice ? n.imageHead = c.slice(0, h) : n.imageHead = new Uint8Array(c).subarray(0, h))
            } else console.log("Invalid JPEG file: Missing JPEG marker.");
            i(n)
        }, "readAsArrayBuffer")) && i(n)
    }
}),
function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["load-image", "load-image-meta"], e) : "object" == typeof module && module.exports ? e(require("./load-image"), require("./load-image-meta")) : e(window.loadImage)
}(function(e) {
    "use strict";
    e.ExifMap = function() {
        return this
    }, e.ExifMap.prototype.map = {
        Orientation: 274
    }, e.ExifMap.prototype.get = function(e) {
        return this[e] || this[this.map[e]]
    }, e.getExifThumbnail = function(e, t, i) {
        var a, o, r;
        if (!i || t + i > e.byteLength) return void console.log("Invalid Exif data: Invalid thumbnail data.");
        for (a = [], o = 0; i > o; o += 1) r = e.getUint8(t + o), a.push((16 > r ? "0" : "") + r.toString(16));
        return "data:image/jpeg,%" + a.join("%")
    }, e.exifTagTypes = {
        1: {
            getValue: function(e, t) {
                return e.getUint8(t)
            },
            size: 1
        },
        2: {
            getValue: function(e, t) {
                return String.fromCharCode(e.getUint8(t))
            },
            size: 1,
            ascii: !0
        },
        3: {
            getValue: function(e, t, i) {
                return e.getUint16(t, i)
            },
            size: 2
        },
        4: {
            getValue: function(e, t, i) {
                return e.getUint32(t, i)
            },
            size: 4
        },
        5: {
            getValue: function(e, t, i) {
                return e.getUint32(t, i) / e.getUint32(t + 4, i)
            },
            size: 8
        },
        9: {
            getValue: function(e, t, i) {
                return e.getInt32(t, i)
            },
            size: 4
        },
        10: {
            getValue: function(e, t, i) {
                return e.getInt32(t, i) / e.getInt32(t + 4, i)
            },
            size: 8
        }
    }, e.exifTagTypes[7] = e.exifTagTypes[1], e.getExifValue = function(t, i, a, o, r, n) {
        var s, d, l, c, u, g, f = e.exifTagTypes[o];
        if (!f) return void console.log("Invalid Exif data: Invalid tag type.");
        if (s = f.size * r, d = s > 4 ? i + t.getUint32(a + 8, n) : a + 8, d + s > t.byteLength) return void console.log("Invalid Exif data: Invalid data offset.");
        if (1 === r) return f.getValue(t, d, n);
        for (l = [], c = 0; r > c; c += 1) l[c] = f.getValue(t, d + c * f.size, n);
        if (f.ascii) {
            for (u = "", c = 0; c < l.length && (g = l[c], "\x00" !== g); c += 1) u += g;
            return u
        }
        return l
    }, e.parseExifTag = function(t, i, a, o, r) {
        var n = t.getUint16(a, o);
        r.exif[n] = e.getExifValue(t, i, a, t.getUint16(a + 2, o), t.getUint32(a + 4, o), o)
    }, e.parseExifTags = function(e, t, i, a, o) {
        var r, n, s;
        if (i + 6 > e.byteLength) return void console.log("Invalid Exif data: Invalid directory offset.");
        if (r = e.getUint16(i, a), n = i + 2 + 12 * r, n + 4 > e.byteLength) return void console.log("Invalid Exif data: Invalid directory size.");
        for (s = 0; r > s; s += 1) this.parseExifTag(e, t, i + 2 + 12 * s, a, o);
        return e.getUint32(n, a)
    }, e.parseExifData = function(t, i, a, o, r) {
        if (!r.disableExif) {
            var n, s, d, l = i + 10;
            if (1165519206 === t.getUint32(i + 4)) {
                if (l + 8 > t.byteLength) return void console.log("Invalid Exif data: Invalid segment size.");
                if (0 !== t.getUint16(i + 8)) return void console.log("Invalid Exif data: Missing byte alignment offset.");
                switch (t.getUint16(l)) {
                    case 18761:
                        n = !0;
                        break;
                    case 19789:
                        n = !1;
                        break;
                    default:
                        return void console.log("Invalid Exif data: Invalid byte alignment marker.")
                }
                if (42 !== t.getUint16(l + 2, n)) return void console.log("Invalid Exif data: Missing TIFF marker.");
                s = t.getUint32(l + 4, n), o.exif = new e.ExifMap, s = e.parseExifTags(t, l, l + s, n, o), s && !r.disableExifThumbnail && (d = {
                    exif: {}
                }, s = e.parseExifTags(t, l, l + s, n, d), d.exif[513] && (o.exif.Thumbnail = e.getExifThumbnail(t, l + d.exif[513], d.exif[514]))), o.exif[34665] && !r.disableExifSub && e.parseExifTags(t, l, l + o.exif[34665], n, o), o.exif[34853] && !r.disableExifGps && e.parseExifTags(t, l, l + o.exif[34853], n, o)
            }
        }
    }, e.metaDataParsers.jpeg[65505].push(e.parseExifData)
}),
function(e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["load-image", "load-image-exif"], e) : "object" == typeof module && module.exports ? e(require("./load-image"), require("./load-image-exif")) : e(window.loadImage)
}(function(e) {
    "use strict";
    e.ExifMap.prototype.tags = {
            256: "ImageWidth",
            257: "ImageHeight",
            34665: "ExifIFDPointer",
            34853: "GPSInfoIFDPointer",
            40965: "InteroperabilityIFDPointer",
            258: "BitsPerSample",
            259: "Compression",
            262: "PhotometricInterpretation",
            274: "Orientation",
            277: "SamplesPerPixel",
            284: "PlanarConfiguration",
            530: "YCbCrSubSampling",
            531: "YCbCrPositioning",
            282: "XResolution",
            283: "YResolution",
            296: "ResolutionUnit",
            273: "StripOffsets",
            278: "RowsPerStrip",
            279: "StripByteCounts",
            513: "JPEGInterchangeFormat",
            514: "JPEGInterchangeFormatLength",
            301: "TransferFunction",
            318: "WhitePoint",
            319: "PrimaryChromaticities",
            529: "YCbCrCoefficients",
            532: "ReferenceBlackWhite",
            306: "DateTime",
            270: "ImageDescription",
            271: "Make",
            272: "Model",
            305: "Software",
            315: "Artist",
            33432: "Copyright",
            36864: "ExifVersion",
            40960: "FlashpixVersion",
            40961: "ColorSpace",
            40962: "PixelXDimension",
            40963: "PixelYDimension",
            42240: "Gamma",
            37121: "ComponentsConfiguration",
            37122: "CompressedBitsPerPixel",
            37500: "MakerNote",
            37510: "UserComment",
            40964: "RelatedSoundFile",
            36867: "DateTimeOriginal",
            36868: "DateTimeDigitized",
            37520: "SubSecTime",
            37521: "SubSecTimeOriginal",
            37522: "SubSecTimeDigitized",
            33434: "ExposureTime",
            33437: "FNumber",
            34850: "ExposureProgram",
            34852: "SpectralSensitivity",
            34855: "PhotographicSensitivity",
            34856: "OECF",
            34864: "SensitivityType",
            34865: "StandardOutputSensitivity",
            34866: "RecommendedExposureIndex",
            34867: "ISOSpeed",
            34868: "ISOSpeedLatitudeyyy",
            34869: "ISOSpeedLatitudezzz",
            37377: "ShutterSpeedValue",
            37378: "ApertureValue",
            37379: "BrightnessValue",
            37380: "ExposureBias",
            37381: "MaxApertureValue",
            37382: "SubjectDistance",
            37383: "MeteringMode",
            37384: "LightSource",
            37385: "Flash",
            37396: "SubjectArea",
            37386: "FocalLength",
            41483: "FlashEnergy",
            41484: "SpatialFrequencyResponse",
            41486: "FocalPlaneXResolution",
            41487: "FocalPlaneYResolution",
            41488: "FocalPlaneResolutionUnit",
            41492: "SubjectLocation",
            41493: "ExposureIndex",
            41495: "SensingMethod",
            41728: "FileSource",
            41729: "SceneType",
            41730: "CFAPattern",
            41985: "CustomRendered",
            41986: "ExposureMode",
            41987: "WhiteBalance",
            41988: "DigitalZoomRatio",
            41989: "FocalLengthIn35mmFilm",
            41990: "SceneCaptureType",
            41991: "GainControl",
            41992: "Contrast",
            41993: "Saturation",
            41994: "Sharpness",
            41995: "DeviceSettingDescription",
            41996: "SubjectDistanceRange",
            42016: "ImageUniqueID",
            42032: "CameraOwnerName",
            42033: "BodySerialNumber",
            42034: "LensSpecification",
            42035: "LensMake",
            42036: "LensModel",
            42037: "LensSerialNumber",
            0: "GPSVersionID",
            1: "GPSLatitudeRef",
            2: "GPSLatitude",
            3: "GPSLongitudeRef",
            4: "GPSLongitude",
            5: "GPSAltitudeRef",
            6: "GPSAltitude",
            7: "GPSTimeStamp",
            8: "GPSSatellites",
            9: "GPSStatus",
            10: "GPSMeasureMode",
            11: "GPSDOP",
            12: "GPSSpeedRef",
            13: "GPSSpeed",
            14: "GPSTrackRef",
            15: "GPSTrack",
            16: "GPSImgDirectionRef",
            17: "GPSImgDirection",
            18: "GPSMapDatum",
            19: "GPSDestLatitudeRef",
            20: "GPSDestLatitude",
            21: "GPSDestLongitudeRef",
            22: "GPSDestLongitude",
            23: "GPSDestBearingRef",
            24: "GPSDestBearing",
            25: "GPSDestDistanceRef",
            26: "GPSDestDistance",
            27: "GPSProcessingMethod",
            28: "GPSAreaInformation",
            29: "GPSDateStamp",
            30: "GPSDifferential",
            31: "GPSHPositioningError"
        }, e.ExifMap.prototype.stringValues = {
            ExposureProgram: {
                0: "Undefined",
                1: "Manual",
                2: "Normal program",
                3: "Aperture priority",
                4: "Shutter priority",
                5: "Creative program",
                6: "Action program",
                7: "Portrait mode",
                8: "Landscape mode"
            },
            MeteringMode: {
                0: "Unknown",
                1: "Average",
                2: "CenterWeightedAverage",
                3: "Spot",
                4: "MultiSpot",
                5: "Pattern",
                6: "Partial",
                255: "Other"
            },
            LightSource: {
                0: "Unknown",
                1: "Daylight",
                2: "Fluorescent",
                3: "Tungsten (incandescent light)",
                4: "Flash",
                9: "Fine weather",
                10: "Cloudy weather",
                11: "Shade",
                12: "Daylight fluorescent (D 5700 - 7100K)",
                13: "Day white fluorescent (N 4600 - 5400K)",
                14: "Cool white fluorescent (W 3900 - 4500K)",
                15: "White fluorescent (WW 3200 - 3700K)",
                17: "Standard light A",
                18: "Standard light B",
                19: "Standard light C",
                20: "D55",
                21: "D65",
                22: "D75",
                23: "D50",
                24: "ISO studio tungsten",
                255: "Other"
            },
            Flash: {
                0: "Flash did not fire",
                1: "Flash fired",
                5: "Strobe return light not detected",
                7: "Strobe return light detected",
                9: "Flash fired, compulsory flash mode",
                13: "Flash fired, compulsory flash mode, return light not detected",
                15: "Flash fired, compulsory flash mode, return light detected",
                16: "Flash did not fire, compulsory flash mode",
                24: "Flash did not fire, auto mode",
                25: "Flash fired, auto mode",
                29: "Flash fired, auto mode, return light not detected",
                31: "Flash fired, auto mode, return light detected",
                32: "No flash function",
                65: "Flash fired, red-eye reduction mode",
                69: "Flash fired, red-eye reduction mode, return light not detected",
                71: "Flash fired, red-eye reduction mode, return light detected",
                73: "Flash fired, compulsory flash mode, red-eye reduction mode",
                77: "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
                79: "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
                89: "Flash fired, auto mode, red-eye reduction mode",
                93: "Flash fired, auto mode, return light not detected, red-eye reduction mode",
                95: "Flash fired, auto mode, return light detected, red-eye reduction mode"
            },
            SensingMethod: {
                1: "Undefined",
                2: "One-chip color area sensor",
                3: "Two-chip color area sensor",
                4: "Three-chip color area sensor",
                5: "Color sequential area sensor",
                7: "Trilinear sensor",
                8: "Color sequential linear sensor"
            },
            SceneCaptureType: {
                0: "Standard",
                1: "Landscape",
                2: "Portrait",
                3: "Night scene"
            },
            SceneType: {
                1: "Directly photographed"
            },
            CustomRendered: {
                0: "Normal process",
                1: "Custom process"
            },
            WhiteBalance: {
                0: "Auto white balance",
                1: "Manual white balance"
            },
            GainControl: {
                0: "None",
                1: "Low gain up",
                2: "High gain up",
                3: "Low gain down",
                4: "High gain down"
            },
            Contrast: {
                0: "Normal",
                1: "Soft",
                2: "Hard"
            },
            Saturation: {
                0: "Normal",
                1: "Low saturation",
                2: "High saturation"
            },
            Sharpness: {
                0: "Normal",
                1: "Soft",
                2: "Hard"
            },
            SubjectDistanceRange: {
                0: "Unknown",
                1: "Macro",
                2: "Close view",
                3: "Distant view"
            },
            FileSource: {
                3: "DSC"
            },
            ComponentsConfiguration: {
                0: "",
                1: "Y",
                2: "Cb",
                3: "Cr",
                4: "R",
                5: "G",
                6: "B"
            },
            Orientation: {
                1: "top-left",
                2: "top-right",
                3: "bottom-right",
                4: "bottom-left",
                5: "left-top",
                6: "right-top",
                7: "right-bottom",
                8: "left-bottom"
            }
        }, e.ExifMap.prototype.getText = function(e) {
            var t = this.get(e);
            switch (e) {
                case "LightSource":
                case "Flash":
                case "MeteringMode":
                case "ExposureProgram":
                case "SensingMethod":
                case "SceneCaptureType":
                case "SceneType":
                case "CustomRendered":
                case "WhiteBalance":
                case "GainControl":
                case "Contrast":
                case "Saturation":
                case "Sharpness":
                case "SubjectDistanceRange":
                case "FileSource":
                case "Orientation":
                    return this.stringValues[e][t];
                case "ExifVersion":
                case "FlashpixVersion":
                    return String.fromCharCode(t[0], t[1], t[2], t[3]);
                case "ComponentsConfiguration":
                    return this.stringValues[e][t[0]] + this.stringValues[e][t[1]] + this.stringValues[e][t[2]] + this.stringValues[e][t[3]];
                case "GPSVersionID":
                    return t[0] + "." + t[1] + "." + t[2] + "." + t[3]
            }
            return String(t)
        },
        function(e) {
            var t, i = e.tags,
                a = e.map;
            for (t in i) i.hasOwnProperty(t) && (a[i[t]] = t)
        }(e.ExifMap.prototype), e.ExifMap.prototype.getAll = function() {
            var e, t, i = {};
            for (e in this) this.hasOwnProperty(e) && (t = this.tags[e], t && (i[t] = this.getText(t)));
            return i
        }
});
//# sourceMappingURL=load-image.all.min.js.map
//
//

!function(t) {
    "use strict";
    var e = t.HTMLCanvasElement && t.HTMLCanvasElement.prototype,
        o = t.Blob && function() {
            try {
                return Boolean(new Blob)
            } catch (t) {
                return !1
            }
        }(),
        n = o && t.Uint8Array && function() {
            try {
                return 100 === new Blob([new Uint8Array(100)]).size
            } catch (t) {
                return !1
            }
        }(),
        r = t.BlobBuilder || t.WebKitBlobBuilder || t.MozBlobBuilder || t.MSBlobBuilder,
        a = /^data:((.*?)(;charset=.*?)?)(;base64)?,/,
        i = (o || r) && t.atob && t.ArrayBuffer && t.Uint8Array && function(t) {
            var e, i, l, u, b, c, d, B, f;
            if (e = t.match(a), !e) throw new Error("invalid data URI");
            for (i = e[2] ? e[1] : "text/plain" + (e[3] || ";charset=US-ASCII"), l = !!e[4], u = t.slice(e[0].length), b = l ? atob(u) : decodeURIComponent(u), c = new ArrayBuffer(b.length), d = new Uint8Array(c), B = 0; B < b.length; B += 1) d[B] = b.charCodeAt(B);
            return o ? new Blob([n ? d : c], {
                type: i
            }) : (f = new r, f.append(c), f.getBlob(i))
        };
    t.HTMLCanvasElement && !e.toBlob && (e.mozGetAsFile ? e.toBlob = function(t, o, n) {
        t(n && e.toDataURL && i ? i(this.toDataURL(o, n)) : this.mozGetAsFile("blob", o))
    } : e.toDataURL && i && (e.toBlob = function(t, e, o) {
        t(i(this.toDataURL(e, o)))
    })), "function" == typeof define && define.amd ? define(function() {
        return i
    }) : "object" == typeof module && module.exports ? module.exports = i : t.dataURLtoBlob = i
}(window);
//# sourceMappingURL=canvas-to-blob.min.js.map
//
//
//
!function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper"], t) : (window.blueimp = window.blueimp || {}, window.blueimp.Gallery = t(window.blueimp.helper || window.jQuery))
}(function(t) {
    "use strict";

    function e(t, i) {
        return void 0 === document.body.style.maxHeight ? null : this && this.options === e.prototype.options ? t && t.length ? (this.list = t, this.num = t.length, this.initOptions(i), void this.initialize()) : void this.console.log("blueimp Gallery: No or empty list provided as first argument.", t) : new e(t, i)
    }
    return t.extend(e.prototype, {
        options: {
            container: "#blueimp-gallery",
            slidesContainer: "div",
            titleElement: "h3",
            displayClass: "blueimp-gallery-display",
            controlsClass: "blueimp-gallery-controls",
            singleClass: "blueimp-gallery-single",
            leftEdgeClass: "blueimp-gallery-left",
            rightEdgeClass: "blueimp-gallery-right",
            playingClass: "blueimp-gallery-playing",
            slideClass: "slide",
            slideLoadingClass: "slide-loading",
            slideErrorClass: "slide-error",
            slideContentClass: "slide-content",
            toggleClass: "toggle",
            prevClass: "prev",
            nextClass: "next",
            closeClass: "close",
            playPauseClass: "play-pause",
            typeProperty: "type",
            titleProperty: "title",
            urlProperty: "href",
            displayTransition: !0,
            clearSlides: !0,
            stretchImages: !1,
            toggleControlsOnReturn: !0,
            toggleSlideshowOnSpace: !0,
            enableKeyboardNavigation: !0,
            closeOnEscape: !0,
            closeOnSlideClick: !0,
            closeOnSwipeUpOrDown: !0,
            emulateTouchEvents: !0,
            stopTouchEventsPropagation: !1,
            hidePageScrollbars: !0,
            disableScroll: !0,
            carousel: !1,
            continuous: !0,
            unloadElements: !0,
            startSlideshow: !1,
            slideshowInterval: 5e3,
            index: 0,
            preloadRange: 2,
            transitionSpeed: 400,
            slideshowTransitionSpeed: void 0,
            event: void 0,
            onopen: void 0,
            onopened: void 0,
            onslide: void 0,
            onslideend: void 0,
            onslidecomplete: void 0,
            onclose: void 0,
            onclosed: void 0
        },
        carouselOptions: {
            hidePageScrollbars: !1,
            toggleControlsOnReturn: !1,
            toggleSlideshowOnSpace: !1,
            enableKeyboardNavigation: !1,
            closeOnEscape: !1,
            closeOnSlideClick: !1,
            closeOnSwipeUpOrDown: !1,
            disableScroll: !1,
            startSlideshow: !0
        },
        console: window.console && "function" == typeof window.console.log ? window.console : {
            log: function() {}
        },
        support: function(e) {
            var i = {
                    touch: void 0 !== window.ontouchstart || window.DocumentTouch && document instanceof DocumentTouch
                },
                s = {
                    webkitTransition: {
                        end: "webkitTransitionEnd",
                        prefix: "-webkit-"
                    },
                    MozTransition: {
                        end: "transitionend",
                        prefix: "-moz-"
                    },
                    OTransition: {
                        end: "otransitionend",
                        prefix: "-o-"
                    },
                    transition: {
                        end: "transitionend",
                        prefix: ""
                    }
                },
                o = function() {
                    var t, s, o = i.transition;
                    document.body.appendChild(e), o && (t = o.name.slice(0, -9) + "ransform", void 0 !== e.style[t] && (e.style[t] = "translateZ(0)", s = window.getComputedStyle(e).getPropertyValue(o.prefix + "transform"), i.transform = {
                        prefix: o.prefix,
                        name: t,
                        translate: !0,
                        translateZ: !!s && "none" !== s
                    })), void 0 !== e.style.backgroundSize && (i.backgroundSize = {}, e.style.backgroundSize = "contain", i.backgroundSize.contain = "contain" === window.getComputedStyle(e).getPropertyValue("background-size"), e.style.backgroundSize = "cover", i.backgroundSize.cover = "cover" === window.getComputedStyle(e).getPropertyValue("background-size")), document.body.removeChild(e)
                };
            return function(t, i) {
                var s;
                for (s in i)
                    if (i.hasOwnProperty(s) && void 0 !== e.style[s]) {
                        t.transition = i[s], t.transition.name = s;
                        break
                    }
            }(i, s), document.body ? o() : t(document).on("DOMContentLoaded", o), i
        }(document.createElement("div")),
        requestAnimationFrame: window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame,
        initialize: function() {
            return this.initStartIndex(), this.initWidget() === !1 ? !1 : (this.initEventListeners(), this.onslide(this.index), this.ontransitionend(), void(this.options.startSlideshow && this.play()))
        },
        slide: function(t, e) {
            window.clearTimeout(this.timeout);
            var i, s, o, n = this.index;
            if (n !== t && 1 !== this.num) {
                if (e || (e = this.options.transitionSpeed), this.support.transform) {
                    for (this.options.continuous || (t = this.circle(t)), i = Math.abs(n - t) / (n - t), this.options.continuous && (s = i, i = -this.positions[this.circle(t)] / this.slideWidth, i !== s && (t = -i * this.num + t)), o = Math.abs(n - t) - 1; o;) o -= 1, this.move(this.circle((t > n ? t : n) - o - 1), this.slideWidth * i, 0);
                    t = this.circle(t), this.move(n, this.slideWidth * i, e), this.move(t, 0, e), this.options.continuous && this.move(this.circle(t - i), -(this.slideWidth * i), 0)
                } else t = this.circle(t), this.animate(n * -this.slideWidth, t * -this.slideWidth, e);
                this.onslide(t)
            }
        },
        getIndex: function() {
            return this.index
        },
        getNumber: function() {
            return this.num
        },
        prev: function() {
            (this.options.continuous || this.index) && this.slide(this.index - 1)
        },
        next: function() {
            (this.options.continuous || this.index < this.num - 1) && this.slide(this.index + 1)
        },
        play: function(t) {
            var e = this;
            window.clearTimeout(this.timeout), this.interval = t || this.options.slideshowInterval, this.elements[this.index] > 1 && (this.timeout = this.setTimeout(!this.requestAnimationFrame && this.slide || function(t, i) {
                e.animationFrameId = e.requestAnimationFrame.call(window, function() {
                    e.slide(t, i)
                })
            }, [this.index + 1, this.options.slideshowTransitionSpeed], this.interval)), this.container.addClass(this.options.playingClass)
        },
        pause: function() {
            window.clearTimeout(this.timeout), this.interval = null, this.container.removeClass(this.options.playingClass)
        },
        add: function(t) {
            var e;
            for (t.concat || (t = Array.prototype.slice.call(t)), this.list.concat || (this.list = Array.prototype.slice.call(this.list)), this.list = this.list.concat(t), this.num = this.list.length, this.num > 2 && null === this.options.continuous && (this.options.continuous = !0, this.container.removeClass(this.options.leftEdgeClass)), this.container.removeClass(this.options.rightEdgeClass).removeClass(this.options.singleClass), e = this.num - t.length; e < this.num; e += 1) this.addSlide(e), this.positionSlide(e);
            this.positions.length = this.num, this.initSlides(!0)
        },
        resetSlides: function() {
            this.slidesContainer.empty(), this.unloadAllSlides(), this.slides = []
        },
        handleClose: function() {
            var t = this.options;
            this.destroyEventListeners(), this.pause(), this.container[0].style.display = "none", this.container.removeClass(t.displayClass).removeClass(t.singleClass).removeClass(t.leftEdgeClass).removeClass(t.rightEdgeClass), t.hidePageScrollbars && (document.body.style.overflow = this.bodyOverflowStyle), this.options.clearSlides && this.resetSlides(), this.options.onclosed && this.options.onclosed.call(this)
        },
        close: function() {
            var t = this,
                e = function(i) {
                    i.target === t.container[0] && (t.container.off(t.support.transition.end, e), t.handleClose())
                };
            this.options.onclose && this.options.onclose.call(this), this.support.transition && this.options.displayTransition ? (this.container.on(this.support.transition.end, e), this.container.removeClass(this.options.displayClass)) : this.handleClose()
        },
        circle: function(t) {
            return (this.num + t % this.num) % this.num
        },
        move: function(t, e, i) {
            this.translateX(t, e, i), this.positions[t] = e
        },
        translate: function(t, e, i, s) {
            var o = this.slides[t].style,
                n = this.support.transition,
                a = this.support.transform;
            o[n.name + "Duration"] = s + "ms", o[a.name] = "translate(" + e + "px, " + i + "px)" + (a.translateZ ? " translateZ(0)" : "")
        },
        translateX: function(t, e, i) {
            this.translate(t, e, 0, i)
        },
        translateY: function(t, e, i) {
            this.translate(t, 0, e, i)
        },
        animate: function(t, e, i) {
            if (!i) return void(this.slidesContainer[0].style.left = e + "px");
            var s = this,
                o = (new Date).getTime(),
                n = window.setInterval(function() {
                    var a = (new Date).getTime() - o;
                    return a > i ? (s.slidesContainer[0].style.left = e + "px", s.ontransitionend(), void window.clearInterval(n)) : void(s.slidesContainer[0].style.left = (e - t) * (Math.floor(a / i * 100) / 100) + t + "px")
                }, 4)
        },
        preventDefault: function(t) {
            t.preventDefault ? t.preventDefault() : t.returnValue = !1
        },
        stopPropagation: function(t) {
            t.stopPropagation ? t.stopPropagation() : t.cancelBubble = !0
        },
        onresize: function() {
            this.initSlides(!0)
        },
        onmousedown: function(t) {
            t.which && 1 === t.which && "VIDEO" !== t.target.nodeName && (t.preventDefault(), (t.originalEvent || t).touches = [{
                pageX: t.pageX,
                pageY: t.pageY
            }], this.ontouchstart(t))
        },
        onmousemove: function(t) {
            this.touchStart && ((t.originalEvent || t).touches = [{
                pageX: t.pageX,
                pageY: t.pageY
            }], this.ontouchmove(t))
        },
        onmouseup: function(t) {
            this.touchStart && (this.ontouchend(t), delete this.touchStart)
        },
        onmouseout: function(e) {
            if (this.touchStart) {
                var i = e.target,
                    s = e.relatedTarget;
                (!s || s !== i && !t.contains(i, s)) && this.onmouseup(e)
            }
        },
        ontouchstart: function(t) {
            this.options.stopTouchEventsPropagation && this.stopPropagation(t);
            var e = (t.originalEvent || t).touches[0];
            this.touchStart = {
                x: e.pageX,
                y: e.pageY,
                time: Date.now()
            }, this.isScrolling = void 0, this.touchDelta = {}
        },
        ontouchmove: function(t) {
            this.options.stopTouchEventsPropagation && this.stopPropagation(t);
            var e, i, s = (t.originalEvent || t).touches[0],
                o = (t.originalEvent || t).scale,
                n = this.index;
            if (!(s.length > 1 || o && 1 !== o))
                if (this.options.disableScroll && t.preventDefault(), this.touchDelta = {
                        x: s.pageX - this.touchStart.x,
                        y: s.pageY - this.touchStart.y
                    }, e = this.touchDelta.x, void 0 === this.isScrolling && (this.isScrolling = this.isScrolling || Math.abs(e) < Math.abs(this.touchDelta.y)), this.isScrolling) this.options.closeOnSwipeUpOrDown && this.translateY(n, this.touchDelta.y + this.positions[n], 0);
                else
                    for (t.preventDefault(), window.clearTimeout(this.timeout), this.options.continuous ? i = [this.circle(n + 1), n, this.circle(n - 1)] : (this.touchDelta.x = e /= !n && e > 0 || n === this.num - 1 && 0 > e ? Math.abs(e) / this.slideWidth + 1 : 1, i = [n], n && i.push(n - 1), n < this.num - 1 && i.unshift(n + 1)); i.length;) n = i.pop(), this.translateX(n, e + this.positions[n], 0)
        },
        ontouchend: function(t) {
            this.options.stopTouchEventsPropagation && this.stopPropagation(t);
            var e, i, s, o, n, a = this.index,
                l = this.options.transitionSpeed,
                r = this.slideWidth,
                h = Number(Date.now() - this.touchStart.time) < 250,
                d = h && Math.abs(this.touchDelta.x) > 20 || Math.abs(this.touchDelta.x) > r / 2,
                c = !a && this.touchDelta.x > 0 || a === this.num - 1 && this.touchDelta.x < 0,
                u = !d && this.options.closeOnSwipeUpOrDown && (h && Math.abs(this.touchDelta.y) > 20 || Math.abs(this.touchDelta.y) > this.slideHeight / 2);
            this.options.continuous && (c = !1), e = this.touchDelta.x < 0 ? -1 : 1, this.isScrolling ? u ? this.close() : this.translateY(a, 0, l) : d && !c ? (i = a + e, s = a - e, o = r * e, n = -r * e, this.options.continuous ? (this.move(this.circle(i), o, 0), this.move(this.circle(a - 2 * e), n, 0)) : i >= 0 && i < this.num && this.move(i, o, 0), this.move(a, this.positions[a] + o, l), this.move(this.circle(s), this.positions[this.circle(s)] + o, l), a = this.circle(s), this.onslide(a)) : this.options.continuous ? (this.move(this.circle(a - 1), -r, l), this.move(a, 0, l), this.move(this.circle(a + 1), r, l)) : (a && this.move(a - 1, -r, l), this.move(a, 0, l), a < this.num - 1 && this.move(a + 1, r, l))
        },
        ontouchcancel: function(t) {
            this.touchStart && (this.ontouchend(t), delete this.touchStart)
        },
        ontransitionend: function(t) {
            var e = this.slides[this.index];
            t && e !== t.target || (this.interval && this.play(), this.setTimeout(this.options.onslideend, [this.index, e]))
        },
        oncomplete: function(e) {
            var i, s = e.target || e.srcElement,
                o = s && s.parentNode;
            s && o && (i = this.getNodeIndex(o), t(o).removeClass(this.options.slideLoadingClass), "error" === e.type ? (t(o).addClass(this.options.slideErrorClass), this.elements[i] = 3) : this.elements[i] = 2, s.clientHeight > this.container[0].clientHeight && (s.style.maxHeight = this.container[0].clientHeight), this.interval && this.slides[this.index] === o && this.play(), this.setTimeout(this.options.onslidecomplete, [i, o]))
        },
        onload: function(t) {
            this.oncomplete(t)
        },
        onerror: function(t) {
            this.oncomplete(t)
        },
        onkeydown: function(t) {
            switch (t.which || t.keyCode) {
                case 13:
                    this.options.toggleControlsOnReturn && (this.preventDefault(t), this.toggleControls());
                    break;
                case 27:
                    this.options.closeOnEscape && (this.close(), t.stopImmediatePropagation());
                    break;
                case 32:
                    this.options.toggleSlideshowOnSpace && (this.preventDefault(t), this.toggleSlideshow());
                    break;
                case 37:
                    this.options.enableKeyboardNavigation && (this.preventDefault(t), this.prev());
                    break;
                case 39:
                    this.options.enableKeyboardNavigation && (this.preventDefault(t), this.next())
            }
        },
        handleClick: function(e) {
            var i = this.options,
                s = e.target || e.srcElement,
                o = s.parentNode,
                n = function(e) {
                    return t(s).hasClass(e) || t(o).hasClass(e)
                };
            n(i.toggleClass) ? (this.preventDefault(e), this.toggleControls()) : n(i.prevClass) ? (this.preventDefault(e), this.prev()) : n(i.nextClass) ? (this.preventDefault(e), this.next()) : n(i.closeClass) ? (this.preventDefault(e), this.close()) : n(i.playPauseClass) ? (this.preventDefault(e), this.toggleSlideshow()) : o === this.slidesContainer[0] ? (this.preventDefault(e), i.closeOnSlideClick ? this.close() : this.toggleControls()) : o.parentNode && o.parentNode === this.slidesContainer[0] && (this.preventDefault(e), this.toggleControls())
        },
        onclick: function(t) {
            return this.options.emulateTouchEvents && this.touchDelta && (Math.abs(this.touchDelta.x) > 20 || Math.abs(this.touchDelta.y) > 20) ? void delete this.touchDelta : this.handleClick(t)
        },
        updateEdgeClasses: function(t) {
            t ? this.container.removeClass(this.options.leftEdgeClass) : this.container.addClass(this.options.leftEdgeClass), t === this.num - 1 ? this.container.addClass(this.options.rightEdgeClass) : this.container.removeClass(this.options.rightEdgeClass)
        },
        handleSlide: function(t) {
            this.options.continuous || this.updateEdgeClasses(t), this.loadElements(t), this.options.unloadElements && this.unloadElements(t), this.setTitle(t)
        },
        onslide: function(t) {
            this.index = t, this.handleSlide(t), this.setTimeout(this.options.onslide, [t, this.slides[t]])
        },
        setTitle: function(t) {
            var e = this.slides[t].firstChild.title,
                i = this.titleElement;
            i.length && (this.titleElement.empty(), e && i[0].appendChild(document.createTextNode(e)))
        },
        setTimeout: function(t, e, i) {
            var s = this;
            return t && window.setTimeout(function() {
                t.apply(s, e || [])
            }, i || 0)
        },
        imageFactory: function(e, i) {
            var s, o, n, a = this,
                l = this.imagePrototype.cloneNode(!1),
                r = e,
                h = this.options.stretchImages,
                d = function(e) {
                    if (!s) {
                        if (e = {
                                type: e.type,
                                target: o
                            }, !o.parentNode) return a.setTimeout(d, [e]);
                        s = !0, t(l).off("load error", d), h && "load" === e.type && (o.style.background = 'url("' + r + '") center no-repeat', o.style.backgroundSize = h), i(e)
                    }
                };
            return "string" != typeof r && (r = this.getItemProperty(e, this.options.urlProperty), n = this.getItemProperty(e, this.options.titleProperty)), h === !0 && (h = "contain"), h = this.support.backgroundSize && this.support.backgroundSize[h] && h, h ? o = this.elementPrototype.cloneNode(!1) : (o = l, l.draggable = !1), n && (o.title = n), t(l).on("load error", d), l.src = r, o
        },
        createElement: function(e, i) {
            var s = e && this.getItemProperty(e, this.options.typeProperty),
                o = s && this[s.split("/")[0] + "Factory"] || this.imageFactory,
                n = e && o.call(this, e, i);
            return n || (n = this.elementPrototype.cloneNode(!1), this.setTimeout(i, [{
                type: "error",
                target: n
            }])), t(n).addClass(this.options.slideContentClass), n
        },
        loadElement: function(e) {
            this.elements[e] || (this.slides[e].firstChild ? this.elements[e] = t(this.slides[e]).hasClass(this.options.slideErrorClass) ? 3 : 2 : (this.elements[e] = 1, t(this.slides[e]).addClass(this.options.slideLoadingClass), this.slides[e].appendChild(this.createElement(this.list[e], this.proxyListener))))
        },
        loadElements: function(t) {
            var e, i = Math.min(this.num, 2 * this.options.preloadRange + 1),
                s = t;
            for (e = 0; i > e; e += 1) s += e * (e % 2 === 0 ? -1 : 1), s = this.circle(s), this.loadElement(s)
        },
        unloadElements: function(t) {
            var e, i;
            for (e in this.elements) this.elements.hasOwnProperty(e) && (i = Math.abs(t - e), i > this.options.preloadRange && i + this.options.preloadRange < this.num && (this.unloadSlide(e), delete this.elements[e]))
        },
        addSlide: function(t) {
            var e = this.slidePrototype.cloneNode(!1);
            e.setAttribute("data-index", t), this.slidesContainer[0].appendChild(e), this.slides.push(e)
        },
        positionSlide: function(t) {
            var e = this.slides[t];
            e.style.width = this.slideWidth + "px", this.support.transform && (e.style.left = t * -this.slideWidth + "px", this.move(t, this.index > t ? -this.slideWidth : this.index < t ? this.slideWidth : 0, 0))
        },
        initSlides: function(e) {
            var i, s;
            for (e || (this.positions = [], this.positions.length = this.num, this.elements = {}, this.imagePrototype = document.createElement("img"), this.elementPrototype = document.createElement("div"), this.slidePrototype = document.createElement("div"), t(this.slidePrototype).addClass(this.options.slideClass), this.slides = this.slidesContainer[0].children, i = this.options.clearSlides || this.slides.length !== this.num), this.slideWidth = this.container[0].offsetWidth, this.slideHeight = this.container[0].offsetHeight, this.slidesContainer[0].style.width = this.num * this.slideWidth + "px", i && this.resetSlides(), s = 0; s < this.num; s += 1) i && this.addSlide(s), this.positionSlide(s);
            this.options.continuous && this.support.transform && (this.move(this.circle(this.index - 1), -this.slideWidth, 0), this.move(this.circle(this.index + 1), this.slideWidth, 0)), this.support.transform || (this.slidesContainer[0].style.left = this.index * -this.slideWidth + "px")
        },
        unloadSlide: function(t) {
            var e, i;
            e = this.slides[t], i = e.firstChild, null !== i && e.removeChild(i)
        },
        unloadAllSlides: function() {
            var t, e;
            for (t = 0, e = this.slides.length; e > t; t++) this.unloadSlide(t)
        },
        toggleControls: function() {
            var t = this.options.controlsClass;
            this.container.hasClass(t) ? this.container.removeClass(t) : this.container.addClass(t)
        },
        toggleSlideshow: function() {
            this.interval ? this.pause() : this.play()
        },
        getNodeIndex: function(t) {
            return parseInt(t.getAttribute("data-index"), 10)
        },
        getNestedProperty: function(t, e) {
            return e.replace(/\[(?:'([^']+)'|"([^"]+)"|(\d+))\]|(?:(?:^|\.)([^\.\[]+))/g, function(e, i, s, o, n) {
                var a = n || i || s || o && parseInt(o, 10);
                e && t && (t = t[a])
            }), t
        },
        getDataProperty: function(e, i) {
            if (e.getAttribute) {
                var s = e.getAttribute("data-" + i.replace(/([A-Z])/g, "-$1").toLowerCase());
                if ("string" == typeof s) {
                    if (/^(true|false|null|-?\d+(\.\d+)?|\{[\s\S]*\}|\[[\s\S]*\])$/.test(s)) try {
                        return t.parseJSON(s)
                    } catch (o) {}
                    return s
                }
            }
        },
        getItemProperty: function(t, e) {
            var i = t[e];
            return void 0 === i && (i = this.getDataProperty(t, e), void 0 === i && (i = this.getNestedProperty(t, e))), i
        },
        initStartIndex: function() {
            var t, e = this.options.index,
                i = this.options.urlProperty;
            if (e && "number" != typeof e)
                for (t = 0; t < this.num; t += 1)
                    if (this.list[t] === e || this.getItemProperty(this.list[t], i) === this.getItemProperty(e, i)) {
                        e = t;
                        break
                    }
            this.index = this.circle(parseInt(e, 10) || 0)
        },
        initEventListeners: function() {
            var e = this,
                i = this.slidesContainer,
                s = function(t) {
                    var i = e.support.transition && e.support.transition.end === t.type ? "transitionend" : t.type;
                    e["on" + i](t)
                };
            t(window).on("resize", s), t(document.body).on("keydown", s), this.container.on("click", s), this.support.touch ? i.on("touchstart touchmove touchend touchcancel", s) : this.options.emulateTouchEvents && this.support.transition && i.on("mousedown mousemove mouseup mouseout", s), this.support.transition && i.on(this.support.transition.end, s), this.proxyListener = s
        },
        destroyEventListeners: function() {
            var e = this.slidesContainer,
                i = this.proxyListener;
            t(window).off("resize", i), t(document.body).off("keydown", i), this.container.off("click", i), this.support.touch ? e.off("touchstart touchmove touchend touchcancel", i) : this.options.emulateTouchEvents && this.support.transition && e.off("mousedown mousemove mouseup mouseout", i), this.support.transition && e.off(this.support.transition.end, i)
        },
        handleOpen: function() {
            this.options.onopened && this.options.onopened.call(this)
        },
        initWidget: function() {
            var e = this,
                i = function(t) {
                    t.target === e.container[0] && (e.container.off(e.support.transition.end, i), e.handleOpen())
                };
            return this.container = t(this.options.container), this.container.length ? (this.slidesContainer = this.container.find(this.options.slidesContainer).first(), this.slidesContainer.length ? (this.titleElement = this.container.find(this.options.titleElement).first(), 1 === this.num && this.container.addClass(this.options.singleClass), this.options.onopen && this.options.onopen.call(this), this.support.transition && this.options.displayTransition ? this.container.on(this.support.transition.end, i) : this.handleOpen(), this.options.hidePageScrollbars && (this.bodyOverflowStyle = document.body.style.overflow, document.body.style.overflow = "hidden"), this.container[0].style.display = "block", this.initSlides(), void this.container.addClass(this.options.displayClass)) : (this.console.log("blueimp Gallery: Slides container not found.", this.options.slidesContainer), !1)) : (this.console.log("blueimp Gallery: Widget container not found.", this.options.container), !1)
        },
        initOptions: function(e) {
            this.options = t.extend({}, this.options), (e && e.carousel || this.options.carousel && (!e || e.carousel !== !1)) && t.extend(this.options, this.carouselOptions), t.extend(this.options, e), this.num < 3 && (this.options.continuous = this.options.continuous ? null : !1), this.support.transition || (this.options.emulateTouchEvents = !1), this.options.event && this.preventDefault(this.options.event)
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper", "./blueimp-gallery"], t) : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    t.extend(e.prototype.options, {
        fullScreen: !1
    });
    var i = e.prototype.initialize,
        s = e.prototype.close;
    return t.extend(e.prototype, {
        getFullScreenElement: function() {
            return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement
        },
        requestFullScreen: function(t) {
            t.requestFullscreen ? t.requestFullscreen() : t.webkitRequestFullscreen ? t.webkitRequestFullscreen() : t.mozRequestFullScreen ? t.mozRequestFullScreen() : t.msRequestFullscreen && t.msRequestFullscreen()
        },
        exitFullScreen: function() {
            document.exitFullscreen ? document.exitFullscreen() : document.webkitCancelFullScreen ? document.webkitCancelFullScreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.msExitFullscreen && document.msExitFullscreen()
        },
        initialize: function() {
            i.call(this), this.options.fullScreen && !this.getFullScreenElement() && this.requestFullScreen(this.container[0])
        },
        close: function() {
            this.getFullScreenElement() === this.container[0] && this.exitFullScreen(), s.call(this)
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper", "./blueimp-gallery"], t) : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    t.extend(e.prototype.options, {
        indicatorContainer: "ol",
        activeIndicatorClass: "active",
        thumbnailProperty: "thumbnail",
        thumbnailIndicators: !0
    });
    var i = e.prototype.initSlides,
        s = e.prototype.addSlide,
        o = e.prototype.resetSlides,
        n = e.prototype.handleClick,
        a = e.prototype.handleSlide,
        l = e.prototype.handleClose;
    return t.extend(e.prototype, {
        createIndicator: function(e) {
            var i, s, o = this.indicatorPrototype.cloneNode(!1),
                n = this.getItemProperty(e, this.options.titleProperty),
                a = this.options.thumbnailProperty;
            return this.options.thumbnailIndicators && (a && (i = this.getItemProperty(e, a)), void 0 === i && (s = e.getElementsByTagName && t(e).find("img")[0], s && (i = s.src)), i && (o.style.backgroundImage = 'url("' + i + '")')), n && (o.title = n), o
        },
        addIndicator: function(t) {
            if (this.indicatorContainer.length) {
                var e = this.createIndicator(this.list[t]);
                e.setAttribute("data-index", t), this.indicatorContainer[0].appendChild(e), this.indicators.push(e)
            }
        },
        setActiveIndicator: function(e) {
            this.indicators && (this.activeIndicator && this.activeIndicator.removeClass(this.options.activeIndicatorClass), this.activeIndicator = t(this.indicators[e]), this.activeIndicator.addClass(this.options.activeIndicatorClass))
        },
        initSlides: function(t) {
            t || (this.indicatorContainer = this.container.find(this.options.indicatorContainer), this.indicatorContainer.length && (this.indicatorPrototype = document.createElement("li"), this.indicators = this.indicatorContainer[0].children)), i.call(this, t)
        },
        addSlide: function(t) {
            s.call(this, t), this.addIndicator(t)
        },
        resetSlides: function() {
            o.call(this), this.indicatorContainer.empty(), this.indicators = []
        },
        handleClick: function(t) {
            var e = t.target || t.srcElement,
                i = e.parentNode;
            if (i === this.indicatorContainer[0]) this.preventDefault(t), this.slide(this.getNodeIndex(e));
            else {
                if (i.parentNode !== this.indicatorContainer[0]) return n.call(this, t);
                this.preventDefault(t), this.slide(this.getNodeIndex(i))
            }
        },
        handleSlide: function(t) {
            a.call(this, t), this.setActiveIndicator(t)
        },
        handleClose: function() {
            this.activeIndicator && this.activeIndicator.removeClass(this.options.activeIndicatorClass), l.call(this)
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper", "./blueimp-gallery"], t) : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    t.extend(e.prototype.options, {
        videoContentClass: "video-content",
        videoLoadingClass: "video-loading",
        videoPlayingClass: "video-playing",
        videoPosterProperty: "poster",
        videoSourcesProperty: "sources"
    });
    var i = e.prototype.handleSlide;
    return t.extend(e.prototype, {
        handleSlide: function(t) {
            i.call(this, t), this.playingVideo && this.playingVideo.pause()
        },
        videoFactory: function(e, i, s) {
            var o, n, a, l, r, h = this,
                d = this.options,
                c = this.elementPrototype.cloneNode(!1),
                u = t(c),
                p = [{
                    type: "error",
                    target: c
                }],
                y = s || document.createElement("video"),
                m = this.getItemProperty(e, d.urlProperty),
                f = this.getItemProperty(e, d.typeProperty),
                g = this.getItemProperty(e, d.titleProperty),
                v = this.getItemProperty(e, d.videoPosterProperty),
                C = this.getItemProperty(e, d.videoSourcesProperty);
            if (u.addClass(d.videoContentClass), g && (c.title = g), y.canPlayType)
                if (m && f && y.canPlayType(f)) y.src = m;
                else
                    for (; C && C.length;)
                        if (n = C.shift(), m = this.getItemProperty(n, d.urlProperty), f = this.getItemProperty(n, d.typeProperty), m && f && y.canPlayType(f)) {
                            y.src = m;
                            break
                        }
            return v && (y.poster = v, o = this.imagePrototype.cloneNode(!1), t(o).addClass(d.toggleClass), o.src = v, o.draggable = !1, c.appendChild(o)), a = document.createElement("a"), a.setAttribute("target", "_blank"), s || a.setAttribute("download", g), a.href = m, y.src && (y.controls = !0, (s || t(y)).on("error", function() {
                h.setTimeout(i, p)
            }).on("pause", function() {
                l = !1, u.removeClass(h.options.videoLoadingClass).removeClass(h.options.videoPlayingClass), r && h.container.addClass(h.options.controlsClass), delete h.playingVideo, h.interval && h.play()
            }).on("playing", function() {
                l = !1, u.removeClass(h.options.videoLoadingClass).addClass(h.options.videoPlayingClass), h.container.hasClass(h.options.controlsClass) ? (r = !0, h.container.removeClass(h.options.controlsClass)) : r = !1
            }).on("play", function() {
                window.clearTimeout(h.timeout), l = !0, u.addClass(h.options.videoLoadingClass), h.playingVideo = y
            }), t(a).on("click", function(t) {
                h.preventDefault(t), l ? y.pause() : y.play()
            }), c.appendChild(s && s.element || y)), c.appendChild(a), this.setTimeout(i, [{
                type: "load",
                target: c
            }]), c
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper", "./blueimp-gallery-video"], t) : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    if (!window.postMessage) return e;
    t.extend(e.prototype.options, {
        vimeoVideoIdProperty: "vimeo",
        vimeoPlayerUrl: "//player.vimeo.com/video/VIDEO_ID?api=1&player_id=PLAYER_ID",
        vimeoPlayerIdPrefix: "vimeo-player-",
        vimeoClickToPlay: !0
    });
    var i = e.prototype.textFactory || e.prototype.imageFactory,
        s = function(t, e, i, s) {
            this.url = t, this.videoId = e, this.playerId = i, this.clickToPlay = s, this.element = document.createElement("div"), this.listeners = {}
        },
        o = 0;
    return t.extend(s.prototype, {
        canPlayType: function() {
            return !0
        },
        on: function(t, e) {
            return this.listeners[t] = e, this
        },
        loadAPI: function() {
            for (var e, i, s = this, o = "//" + ("https" === location.protocol ? "secure-" : "") + "a.vimeocdn.com/js/froogaloop2.min.js", n = document.getElementsByTagName("script"), a = n.length, l = function() {
                    !i && s.playOnReady && s.play(), i = !0
                }; a;)
                if (a -= 1, n[a].src === o) {
                    e = n[a];
                    break
                }
            e || (e = document.createElement("script"), e.src = o), t(e).on("load", l), n[0].parentNode.insertBefore(e, n[0]), /loaded|complete/.test(e.readyState) && l()
        },
        onReady: function() {
            var t = this;
            this.ready = !0, this.player.addEvent("play", function() {
                t.hasPlayed = !0, t.onPlaying()
            }), this.player.addEvent("pause", function() {
                t.onPause()
            }), this.player.addEvent("finish", function() {
                t.onPause()
            }), this.playOnReady && this.play()
        },
        onPlaying: function() {
            this.playStatus < 2 && (this.listeners.playing(), this.playStatus = 2)
        },
        onPause: function() {
            this.listeners.pause(), delete this.playStatus
        },
        insertIframe: function() {
            var t = document.createElement("iframe");
            t.src = this.url.replace("VIDEO_ID", this.videoId).replace("PLAYER_ID", this.playerId), t.id = this.playerId, this.element.parentNode.replaceChild(t, this.element), this.element = t
        },
        play: function() {
            var t = this;
            this.playStatus || (this.listeners.play(), this.playStatus = 1), this.ready ? !this.hasPlayed && (this.clickToPlay || window.navigator && /iP(hone|od|ad)/.test(window.navigator.platform)) ? this.onPlaying() : this.player.api("play") : (this.playOnReady = !0, window.$f ? this.player || (this.insertIframe(), this.player = $f(this.element), this.player.addEvent("ready", function() {
                t.onReady()
            })) : this.loadAPI())
        },
        pause: function() {
            this.ready ? this.player.api("pause") : this.playStatus && (delete this.playOnReady, this.listeners.pause(), delete this.playStatus)
        }
    }), t.extend(e.prototype, {
        VimeoPlayer: s,
        textFactory: function(t, e) {
            var n = this.options,
                a = this.getItemProperty(t, n.vimeoVideoIdProperty);
            return a ? (void 0 === this.getItemProperty(t, n.urlProperty) && (t[n.urlProperty] = "//vimeo.com/" + a), o += 1, this.videoFactory(t, e, new s(n.vimeoPlayerUrl, a, n.vimeoPlayerIdPrefix + o, n.vimeoClickToPlay))) : i.call(this, t, e)
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["./blueimp-helper", "./blueimp-gallery-video"], t) : t(window.blueimp.helper || window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    if (!window.postMessage) return e;
    t.extend(e.prototype.options, {
        youTubeVideoIdProperty: "youtube",
        youTubePlayerVars: {
            wmode: "transparent"
        },
        youTubeClickToPlay: !0
    });
    var i = e.prototype.textFactory || e.prototype.imageFactory,
        s = function(t, e, i) {
            this.videoId = t, this.playerVars = e, this.clickToPlay = i, this.element = document.createElement("div"), this.listeners = {}
        };
    return t.extend(s.prototype, {
        canPlayType: function() {
            return !0
        },
        on: function(t, e) {
            return this.listeners[t] = e, this
        },
        loadAPI: function() {
            var t, e = this,
                i = window.onYouTubeIframeAPIReady,
                s = "//www.youtube.com/iframe_api",
                o = document.getElementsByTagName("script"),
                n = o.length;
            for (window.onYouTubeIframeAPIReady = function() {
                    i && i.apply(this), e.playOnReady && e.play()
                }; n;)
                if (n -= 1, o[n].src === s) return;
            t = document.createElement("script"), t.src = s, o[0].parentNode.insertBefore(t, o[0])
        },
        onReady: function() {
            this.ready = !0, this.playOnReady && this.play()
        },
        onPlaying: function() {
            this.playStatus < 2 && (this.listeners.playing(), this.playStatus = 2)
        },
        onPause: function() {
            e.prototype.setTimeout.call(this, this.checkSeek, null, 2e3)
        },
        checkSeek: function() {
            (this.stateChange === YT.PlayerState.PAUSED || this.stateChange === YT.PlayerState.ENDED) && (this.listeners.pause(), delete this.playStatus)
        },
        onStateChange: function(t) {
            switch (t.data) {
                case YT.PlayerState.PLAYING:
                    this.hasPlayed = !0, this.onPlaying();
                    break;
                case YT.PlayerState.PAUSED:
                case YT.PlayerState.ENDED:
                    this.onPause()
            }
            this.stateChange = t.data
        },
        onError: function(t) {
            this.listeners.error(t)
        },
        play: function() {
            var t = this;
            this.playStatus || (this.listeners.play(), this.playStatus = 1), this.ready ? !this.hasPlayed && (this.clickToPlay || window.navigator && /iP(hone|od|ad)/.test(window.navigator.platform)) ? this.onPlaying() : this.player.playVideo() : (this.playOnReady = !0, window.YT && YT.Player ? this.player || (this.player = new YT.Player(this.element, {
                videoId: this.videoId,
                playerVars: this.playerVars,
                events: {
                    onReady: function() {
                        t.onReady()
                    },
                    onStateChange: function(e) {
                        t.onStateChange(e)
                    },
                    onError: function(e) {
                        t.onError(e)
                    }
                }
            })) : this.loadAPI())
        },
        pause: function() {
            this.ready ? this.player.pauseVideo() : this.playStatus && (delete this.playOnReady, this.listeners.pause(), delete this.playStatus)
        }
    }), t.extend(e.prototype, {
        YouTubePlayer: s,
        textFactory: function(t, e) {
            var o = this.options,
                n = this.getItemProperty(t, o.youTubeVideoIdProperty);
            return n ? (void 0 === this.getItemProperty(t, o.urlProperty) && (t[o.urlProperty] = "//www.youtube.com/watch?v=" + n), void 0 === this.getItemProperty(t, o.videoPosterProperty) && (t[o.videoPosterProperty] = "//img.youtube.com/vi/" + n + "/maxresdefault.jpg"), this.videoFactory(t, e, new s(n, o.youTubePlayerVars, o.youTubeClickToPlay))) : i.call(this, t, e)
        }
    }), e
}),
function(t) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery", "./blueimp-gallery"], t) : t(window.jQuery, window.blueimp.Gallery)
}(function(t, e) {
    "use strict";
    t(document).on("click", "[data-gallery]", function(i) {
        var s = t(this).data("gallery"),
            o = t(s),
            n = o.length && o || t(e.prototype.options.container),
            a = {
                onopen: function() {
                    n.data("gallery", this).trigger("open")
                },
                onopened: function() {
                    n.trigger("opened")
                },
                onslide: function() {
                    n.trigger("slide", arguments)
                },
                onslideend: function() {
                    n.trigger("slideend", arguments)
                },
                onslidecomplete: function() {
                    n.trigger("slidecomplete", arguments)
                },
                onclose: function() {
                    n.trigger("close")
                },
                onclosed: function() {
                    n.trigger("closed").removeData("gallery")
                }
            },
            l = t.extend(n.data(), {
                container: n[0],
                index: this,
                event: i
            }, a),
            r = t('[data-gallery="' + s + '"]');
        return l.filter && (r = r.filter(l.filter)), new e(r, l)
    })
});
//# sourceMappingURL=jquery.blueimp-gallery.min.js.map
//
//

