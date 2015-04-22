(function ($) {
	var methods = {
		settings: {
			updateOnResize: false,
			contentHeight: false,
			type: 'height',
			offset: 0,
			minHeight: 0,
			onCalculate: function($target_object, $height) {
				$target_object.css(this.type, $height);
			},
			onResize: function($target_object, $height) {
				$target_object.css(methods.settings.type, $height);
			}
		},
		children: function($object, $target_object, $height) {
			$object.children().each(function() {
				var $self = $(this);

				if($target_object.is($self) || $self.is(':hidden')) return;

				if($self.find($target_object).size() > 0) {
					$height -= ($self.outerHeight(true) - $self.outerHeight());
					$height = methods.children($self, $target_object, $height);
				} else {
					$height -= $self.outerHeight(true);
				}
			});

			return $height;
		},
		get_calculated_height: function($object, $target_object) {
			var height = this.get_content_height($object);
			height = this.children($object, $target_object, height);

			height -= parseInt(methods.settings.offset);

			if(height < methods.settings.minHeight)
				height = methods.settings.minHeight;

			return height;
		},
		get_content_height: function($object) {
			var height = 0;

			if(this.settings.contentHeight) {
				if((typeof this.settings.contentHeight == "boolean") || this.settings.contentHeight == 'auto')
					height = CMS.calculateContentHeight();
				else
					height = parseInt(this.settings.contentHeight);
			} else
				height = $object.height();

			return height - 3;
		},
		get_taget_object: function($object, $container) {
			if (typeof $object == 'string' || $object instanceof String)
				return $($object, $container);
			else if($target_object instanceof jQuery)
				return $object;
			else return false;
		}
	}

	$.fn.calcHeightFor = function($object, options) {

		methods.settings = $.extend(methods.settings, options);
		var $self = $(this);

		$target_object = methods.get_taget_object($object, this);
		if(!$target_object || $self.find($target_object).size() == 0) return;

		var contHeight = methods.get_content_height($self);
		contHeight = methods.children($(this), $target_object, contHeight);

		return contHeight;
	};

	$.fn.setHeightFor = function($object, options) {
		methods.settings = $.extend(methods.settings, options);

		return this.each(function(){
			var $self = $(this);

			$target_object = methods.get_taget_object($object, this);
			if(!$target_object || $self.find($target_object).size() == 0) return;

			var $size = $target_object.size();
			$target_object.each(function() {
				var $target_object = $(this);
				var height = methods.get_calculated_height($self, $target_object) / $size;
				methods.settings.onCalculate($target_object, height);

				if(methods.settings.updateOnResize) {
					$(window).on('resize', $self, function() {
						var height = methods.get_calculated_height($self, $target_object) / $size;
						methods.settings.onResize($target_object, height);
					});
				}
			});
		});
	};
})(jQuery);


function strtr(e, t, n) {
	if (typeof t === "object") {
		var r = "";
		for (var i = 0; i < e.length; i++) {
			r += "0"
		}
		var s = 0;
		var o = -1;
		var u = "";
		for (fr in t) {
			s = 0;
			while ((o = e.indexOf(fr, s)) != -1) {
				if (parseInt(r.substr(o, fr.length)) != 0) {
					s = o + 1;
					continue
				}
				for (var a = 0; a < t[fr].length; a++) {
					u += "1"
				}
				r = r.substr(0, o) + u + r.substr(o + fr.length, r.length - (o + fr.length));
				e = e.substr(0, o) + t[fr] + e.substr(o + fr.length, e.length - (o + fr.length));
				s = o + t[fr].length + 1;
				u = ""
			}
		}
		return e
	}
	for (var f = 0; f < t.length; f++) {
		e = e.replace(new RegExp(t.charAt(f), "g"), n.charAt(f))
	}
	return e
}

function __(e, t) {
	if (CMS.translations[e] !== undefined) {
		var e = CMS.translations[e]
	}
	return t == undefined ? e : strtr(e, t)
}

function readImage(input, target) {
	if (input.files && input.files[0] && target) {
		var FR = new FileReader();
		FR.onload = function(e) {
			var img = new Image();
			img.src = e.target.result;

			var ratio = img.width / img.height;

			var canvas = document.createElement("canvas");
			canvas.width = 100 * ratio;
			canvas.height = 100;

			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
			target.attr("src", canvas.toDataURL("image/jpeg", 1));
		};
		FR.readAsDataURL(input.files[0]);
	}
}