var sortable = {
  mixins: [Class, Animate],
  props: {
    group: String,
    threshold: Number,
    clsItem: String,
    clsPlaceholder: String,
    clsDrag: String,
    clsDragState: String,
    clsBase: String,
    clsNoDrag: String,
    clsEmpty: String,
    clsCustom: String,
    handle: String
  },
  data: {
    group: false,
    threshold: 5,
    clsItem: "uk-sortable-item",
    clsPlaceholder: "uk-sortable-placeholder",
    clsDrag: "uk-sortable-drag",
    clsDragState: "uk-drag",
    clsBase: "uk-sortable",
    clsNoDrag: "uk-sortable-nodrag",
    clsEmpty: "uk-sortable-empty",
    clsCustom: "",
    handle: false,
    pos: {}
  },
  created() {
    for (const key of ["init", "start", "move", "end"]) {
      const fn = this[key];
      this[key] = (e) => {
        assign(this.pos, getEventPos(e));
        fn(e);
      };
    }
  },
  events: {
    name: pointerDown$1,
    passive: false,
    handler: "init"
  },
  computed: {
    target: (_, $el) => ($el.tBodies || [$el])[0],
    items() {
      return children(this.target);
    },
    isEmpty() {
      return !this.items.length;
    },
    handles({ handle }, $el) {
      return handle ? $$(handle, $el) : this.items;
    }
  },
  watch: {
    isEmpty(empty) {
      toggleClass(this.target, this.clsEmpty, empty);
    },
    handles(handles, prev) {
      css(prev, { touchAction: "", userSelect: "" });
      css(handles, { touchAction: "none", userSelect: "none" });
    }
  },
  update: {
    write(data) {
      if (!this.drag || !parent(this.placeholder)) {
        return;
      }
      const {
        pos: { x, y },
        origin: { offsetTop, offsetLeft },
        placeholder
      } = this;
      css(this.drag, {
        top: y - offsetTop,
        left: x - offsetLeft
      });
      const sortable = this.getSortable(document.elementFromPoint(x, y));
      if (!sortable) {
        return;
      }
      const { items } = sortable;
      if (items.some(Transition.inProgress)) {
        return;
      }
      const target = findTarget(items, { x, y });
      if (items.length && (!target || target === placeholder)) {
        return;
      }
      const previous = this.getSortable(placeholder);
      const insertTarget = findInsertTarget(
        sortable.target,
        target,
        placeholder,
        x,
        y,
        sortable === previous && data.moved !== target
      );
      if (insertTarget === false) {
        return;
      }
      if (insertTarget && placeholder === insertTarget) {
        return;
      }
      if (sortable !== previous) {
        previous.remove(placeholder);
        data.moved = target;
      } else {
        delete data.moved;
      }
      sortable.insert(placeholder, insertTarget);
      this.touched.add(sortable);
    },
    events: ["move"]
  },
  methods: {
    init(e) {
      const { target, button, defaultPrevented } = e;
      const [placeholder] = this.items.filter((el) => el.contains(target));
      if (!placeholder || defaultPrevented || button > 0 || isInput(target) || target.closest(`.${this.clsNoDrag}`) || this.handle && !target.closest(this.handle)) {
        return;
      }
      e.preventDefault();
      this.touched = /* @__PURE__ */ new Set([this]);
      this.placeholder = placeholder;
      this.origin = { target, index: index(placeholder), ...this.pos };
      on(document, pointerMove$1, this.move);
      on(document, pointerUp$1, this.end);
      if (!this.threshold) {
        this.start(e);
      }
    },
    start(e) {
      this.drag = appendDrag(this.$container, this.placeholder);
      const { left, top } = dimensions$1(this.placeholder);
      assign(this.origin, { offsetLeft: this.pos.x - left, offsetTop: this.pos.y - top });
      addClass(this.drag, this.clsDrag, this.clsCustom);
      addClass(this.placeholder, this.clsPlaceholder);
      addClass(this.items, this.clsItem);
      addClass(document.documentElement, this.clsDragState);
      trigger(this.$el, "start", [this, this.placeholder]);
      trackScroll(this.pos);
      this.move(e);
    },
    move(e) {
      if (this.drag) {
        this.$emit("move");
      } else if (Math.abs(this.pos.x - this.origin.x) > this.threshold || Math.abs(this.pos.y - this.origin.y) > this.threshold) {
        this.start(e);
      }
    },
    end() {
      off(document, pointerMove$1, this.move);
      off(document, pointerUp$1, this.end);
      if (!this.drag) {
        return;
      }
      untrackScroll();
      const sortable = this.getSortable(this.placeholder);
      if (this === sortable) {
        if (this.origin.index !== index(this.placeholder)) {
          trigger(this.$el, "moved", [this, this.placeholder]);
        }
      } else {
        trigger(sortable.$el, "added", [sortable, this.placeholder]);
        trigger(this.$el, "removed", [this, this.placeholder]);
      }
      trigger(this.$el, "stop", [this, this.placeholder]);
      remove$1(this.drag);
      this.drag = null;
      for (const { clsPlaceholder, clsItem } of this.touched) {
        for (const sortable2 of this.touched) {
          removeClass(sortable2.items, clsPlaceholder, clsItem);
        }
      }
      this.touched = null;
      removeClass(document.documentElement, this.clsDragState);
    },
    insert(element, target) {
      addClass(this.items, this.clsItem);
      const insert = () => target ? before(target, element) : append(this.target, element);
      this.animate(insert);
    },
    remove(element) {
      if (!this.target.contains(element)) {
        return;
      }
      this.animate(() => remove$1(element));
    },
    getSortable(element) {
      do {
        const sortable = this.$getComponent(element, "sortable");
        if (sortable && (sortable === this || this.group !== false && sortable.group === this.group)) {
          return sortable;
        }
      } while (element = parent(element));
    }
  }
};

var components$1 = /*#__PURE__*/Object.freeze({
  __proto__: null,
  Countdown: countdown,
  Filter: filter,
  Lightbox: lightbox,
  LightboxPanel: LightboxPanel,
  Notification: notification,
  Parallax: parallax,
  Slider: slider,
  SliderParallax: sliderParallax,
  Slideshow: slideshow,
  SlideshowParallax: sliderParallax,
  Sortable: sortable,
  Tooltip: tooltip,
  Upload: upload
});