window.isRtl = window.Helpers.isRtl();
window.isDarkStyle = window.Helpers.isDarkStyle();
let y, b = !1;
document.getElementById("layout-menu") && (b = document.getElementById("layout-menu").classList.contains("menu-horizontal"));
(function () {
  var L, x;
  typeof Waves < "u" && (Waves.init(), Waves.attach(".btn[class*='btn-']:not(.position-relative):not([class*='btn-outline-']):not([class*='btn-label-'])", ["waves-light"]), Waves.attach("[class*='btn-outline-']:not(.position-relative)"), Waves.attach("[class*='btn-label-']:not(.position-relative)"), Waves.attach(".pagination .page-item .page-link"), Waves.attach(".dropdown-menu .dropdown-item"), Waves.attach(".light-style .list-group .list-group-item-action"), Waves.attach(".dark-style .list-group .list-group-item-action", ["waves-light"]), Waves.attach(".nav-tabs:not(.nav-tabs-widget) .nav-item .nav-link"), Waves.attach(".nav-pills .nav-item .nav-link", ["waves-light"]), Waves.attach(".menu-vertical .menu-item .menu-link.menu-toggle"));

  function f() {
    var e = document.querySelector(".layout-page");
    e && (window.pageYOffset > 0 ? e.classList.add("window-scrolled") : e.classList.remove("window-scrolled"))
  }
  setTimeout(() => {
    f()
  }, 200), window.onscroll = function () {
    f()
  }, setTimeout(function () {
    window.Helpers.initCustomOptionCheck()
  }, 1e3), document.querySelectorAll("#layout-menu").forEach(function (e) {
    y = new Menu(e, {
      orientation: b ? "horizontal" : "vertical",
      closeChildren: !!b,
      showDropdownOnHover: localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") ? localStorage.getItem("templateCustomizer-" + templateName + "--ShowDropdownOnHover") === "true" : window.templateCustomizer !== void 0 ? window.templateCustomizer.settings.defaultShowDropdownOnHover : !0
    }), window.Helpers.scrollToActive(!1), window.Helpers.mainMenu = y
  }), document.querySelectorAll(".layout-menu-toggle").forEach(e => {
    e.addEventListener("click", s => {
      if (s.preventDefault(), window.Helpers.toggleCollapsed(), config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) try {
        localStorage.setItem("templateCustomizer-" + templateName + "--LayoutCollapsed", String(window.Helpers.isCollapsed()));
        let a = document.querySelector(".template-customizer-layouts-options");
        if (a) {
          let n = window.Helpers.isCollapsed() ? "collapsed" : "expanded";
          a.querySelector(`input[value="${n}"]`).click()
        }
      } catch {}
    })
  }), window.Helpers.swipeIn(".drag-target", function (e) {
    window.Helpers.setCollapsed(!1)
  }), window.Helpers.swipeOut("#layout-menu", function (e) {
    window.Helpers.isSmallScreen() && window.Helpers.setCollapsed(!0)
  });
  let p = document.getElementsByClassName("menu-inner"),
    u = document.getElementsByClassName("menu-inner-shadow")[0];
  p.length > 0 && u && p[0].addEventListener("ps-scroll-y", function () {
    this.querySelector(".ps__thumb-y").offsetTop ? u.style.display = "block" : u.style.display = "none"
  });

  function h(e) {
    e === "system" && (window.matchMedia("(prefers-color-scheme: dark)").matches ? e = "dark" : e = "light"), [].slice.call(document.querySelectorAll("[data-app-" + e + "-img]")).map(function (a) {
      const n = a.getAttribute("data-app-" + e + "-img");
      a.src = assetsPath + "img/" + n
    })
  }
  let m = document.querySelector(".dropdown-style-switcher");
  const g = document.documentElement.getAttribute("data-style");
  let o = localStorage.getItem("templateCustomizer-" + templateName + "--Style") || (((x = (L = window.templateCustomizer) == null ? void 0 : L.settings) == null ? void 0 : x.defaultStyle) ? ? "light"); //!if there is no Customizer then use default style as light
  if (window.templateCustomizer && m) {
    [].slice.call(m.children[1].querySelectorAll(".dropdown-item")).forEach(function (a) {
      a.classList.remove("active"), a.addEventListener("click", function () {
        let n = this.getAttribute("data-theme");
        n === "light" ? window.templateCustomizer.setStyle("light") : n === "dark" ? window.templateCustomizer.setStyle("dark") : window.templateCustomizer.setStyle("system")
      }), a.getAttribute("data-theme") === g && a.classList.add("active")
    });
    const s = m.querySelector("i");
    o === "light" ? (s.classList.add("ri-sun-line"), new bootstrap.Tooltip(s, {
      title: "Light Mode",
      fallbackPlacements: ["bottom"]
    })) : o === "dark" ? (s.classList.add("ri-moon-clear-line"), new bootstrap.Tooltip(s, {
      title: "Dark Mode",
      fallbackPlacements: ["bottom"]
    })) : (s.classList.add("ri-computer-line"), new bootstrap.Tooltip(s, {
      title: "System Mode",
      fallbackPlacements: ["bottom"]
    }))
  }
  h(o);
  let i = document.getElementsByClassName("dropdown-language");
  if (i.length) {
    let a = function (n) {
      n === "rtl" ? localStorage.getItem("templateCustomizer-" + templateName + "--Rtl") !== "true" && window.templateCustomizer && window.templateCustomizer.setRtl(!0) : localStorage.getItem("templateCustomizer-" + templateName + "--Rtl") === "true" && window.templateCustomizer && window.templateCustomizer.setRtl(!1)
    };
    var k = a;
    let e = i[0].querySelectorAll(".dropdown-item");
    const s = i[0].querySelector(".dropdown-item.active");
    a(s.dataset.textDirection);
    for (let n = 0; n < e.length; n++) e[n].addEventListener("click", function () {
      let H = this.getAttribute("data-text-direction");
      window.templateCustomizer.setLang(this.getAttribute("data-language")), a(H)
    })
  }
  setTimeout(function () {
    let e = document.querySelector(".template-customizer-reset-btn");
    e && (e.onclick = function () {
      window.location.href = baseUrl + "lang/en"
    })
  }, 1500);
  const t = document.querySelector(".dropdown-notifications-all"),
    r = document.querySelectorAll(".dropdown-notifications-read");
  t && t.addEventListener("click", e => {
    r.forEach(s => {
      s.closest(".dropdown-notifications-item").classList.add("marked-as-read")
    })
  }), r && r.forEach(e => {
    e.addEventListener("click", s => {
      e.closest(".dropdown-notifications-item").classList.toggle("marked-as-read")
    })
  }), document.querySelectorAll(".dropdown-notifications-archive").forEach(e => {
    e.addEventListener("click", s => {
      e.closest(".dropdown-notifications-item").remove()
    })
  }), [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (e) {
    return new bootstrap.Tooltip(e)
  });
  const v = function (e) {
    var s, a;
    e.type == "show.bs.collapse" || e.type == "show.bs.collapse" ? (e.target.closest(".accordion-item").classList.add("active"), (s = e.target.closest(".accordion-item").previousElementSibling) == null || s.classList.add("previous-active")) : (e.target.closest(".accordion-item").classList.remove("active"), (a = e.target.closest(".accordion-item").previousElementSibling) == null || a.classList.remove("previous-active"))
  };
  [].slice.call(document.querySelectorAll(".accordion")).map(function (e) {
    e.addEventListener("show.bs.collapse", v), e.addEventListener("hide.bs.collapse", v)
  }), window.Helpers.setAutoUpdate(!0), window.Helpers.initPasswordToggle(), window.Helpers.initSpeechToText(), window.Helpers.navTabsAnimation(), window.Helpers.initNavbarDropdownScrollbar();
  let C = document.querySelector("[data-template^='horizontal-menu']");
  if (C && (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT ? window.Helpers.setNavbarFixed("fixed") : window.Helpers.setNavbarFixed("")), window.addEventListener("resize", function (e) {
      window.innerWidth >= window.Helpers.LAYOUT_BREAKPOINT && document.querySelector(".search-input-wrapper") && (document.querySelector(".search-input-wrapper").classList.add("d-none"), document.querySelector(".search-input").value = ""), C && (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT ? window.Helpers.setNavbarFixed("fixed") : window.Helpers.setNavbarFixed(""), setTimeout(function () {
        window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT ? document.getElementById("layout-menu") && document.getElementById("layout-menu").classList.contains("menu-horizontal") && y.switchMenu("vertical") : document.getElementById("layout-menu") && document.getElementById("layout-menu").classList.contains("menu-vertical") && y.switchMenu("horizontal")
      }, 100)), window.Helpers.navTabsAnimation()
    }, !0), !(b || window.Helpers.isSmallScreen()) && (typeof TemplateCustomizer < "u" && (window.templateCustomizer.settings.defaultMenuCollapsed ? window.Helpers.setCollapsed(!0, !1) : window.Helpers.setCollapsed(!1, !1)), typeof config < "u" && config.enableMenuLocalStorage)) try {
    localStorage.getItem("templateCustomizer-" + templateName + "--LayoutCollapsed") !== null && window.Helpers.setCollapsed(localStorage.getItem("templateCustomizer-" + templateName + "--LayoutCollapsed") === "true", !1)
  } catch {}
})();
typeof $ < "u" && $(function () {
  window.Helpers.initSidebarToggle();
  var f = $(".search-toggler"),
    c = $(".search-input-wrapper"),
    l = $(".search-input"),
    p = $(".content-backdrop");
  if (f.length && f.on("click", function () {
      c.length && (c.toggleClass("d-none"), l.focus())
    }), $(document).on("keydown", function (o) {
      let i = o.ctrlKey,
        t = o.which === 191;
      i && t && c.length && (c.toggleClass("d-none"), l.focus())
    }), setTimeout(function () {
      var o = $(".twitter-typeahead");
      l.on("focus", function () {
        c.hasClass("container-xxl") ? (c.find(o).addClass("container-xxl"), o.removeClass("container-fluid")) : c.hasClass("container-fluid") && (c.find(o).addClass("container-fluid"), o.removeClass("container-xxl"))
      })
    }, 10), l.length) {
    var u = function (o) {
        return function (t, r) {
          let d;
          d = [], o.filter(function (w) {
            if (w.name.toLowerCase().startsWith(t.toLowerCase())) d.push(w);
            else if (!w.name.toLowerCase().startsWith(t.toLowerCase()) && w.name.toLowerCase().includes(t.toLowerCase())) d.push(w), d.sort(function (v, S) {
              return S.name < v.name ? 1 : -1
            });
            else return []
          }), r(d)
        }
      },
      h = "search-vertical.json";
    if ($("#layout-menu").hasClass("menu-horizontal")) var h = "search-horizontal.json";
    var m = $.ajax({
      url: assetsPath + "json/" + h,
      dataType: "json",
      async: !1
    }).responseJSON;
    l.each(function () {
      var o = $(this);
      l.typeahead({
        hint: !1,
        classNames: {
          menu: "tt-menu navbar-search-suggestion",
          cursor: "active",
          suggestion: "suggestion d-flex justify-content-between px-3 py-2 w-100"
        }
      }, {
        name: "pages",
        display: "name",
        limit: 5,
        source: u(m.pages),
        templates: {
          header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Pages</h6>',
          suggestion: function ({
            url: i,
            icon: t,
            name: r
          }) {
            return '<a href="' + baseUrl + i + '"><div><i class="' + t + ' me-2"></i><span class="align-middle">' + r + "</span></div></a>"
          },
          notFound: '<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Pages</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'
        }
      }, {
        name: "files",
        display: "name",
        limit: 4,
        source: u(m.files),
        templates: {
          header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Files</h6>',
          suggestion: function ({
            src: i,
            name: t,
            subtitle: r,
            meta: d
          }) {
            return '<a href="javascript:;"><div class="d-flex w-50"><img class="me-3" src="' + assetsPath + i + '" alt="' + t + '" height="32"><div class="w-75"><h6 class="mb-0">' + t + '</h6><small class="text-muted">' + r + '</small></div></div><small class="text-muted">' + d + "</small></a>"
          },
          notFound: '<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Files</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'
        }
      }, {
        name: "members",
        display: "name",
        limit: 4,
        source: u(m.members),
        templates: {
          header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">Members</h6>',
          suggestion: function ({
            name: i,
            src: t,
            subtitle: r
          }) {
            return '<a href="' + baseUrl + 'app/user/view/account"><div class="d-flex align-items-center"><img class="rounded-circle me-3" src="' + assetsPath + t + '" alt="' + i + '" height="32"><div class="user-info"><h6 class="mb-0">' + i + '</h6><small class="text-muted">' + r + "</small></div></div></a>"
          },
          notFound: '<div class="not-found px-3 py-2"><h6 class="suggestions-header text-primary mb-2">Members</h6><p class="py-2 mb-0"><i class="ri-warning-line me-2 ri-14px"></i> No Results Found</p></div>'
        }
      }).bind("typeahead:render", function () {
        p.addClass("show").removeClass("fade")
      }).bind("typeahead:select", function (i, t) {
        t.url !== "javascript:;" && (window.location = baseUrl + t.url)
      }).bind("typeahead:close", function () {
        l.val(""), o.typeahead("val", ""), c.addClass("d-none"), p.addClass("fade").removeClass("show")
      }), l.on("keyup", function () {
        l.val() == "" && p.addClass("fade").removeClass("show")
      })
    });
    var g;
    $(".navbar-search-suggestion").each(function () {
      g = new PerfectScrollbar($(this)[0], {
        wheelPropagation: !1,
        suppressScrollX: !0
      })
    }), l.on("keyup", function () {
      g.update()
    })
  }
});