# Get the directory that this configuration file exists in
dir = File.dirname(__FILE__)

# It's a magic name that compass knows how to find.
stylesheets = File.join(dir, 'stylesheets')
Compass::Frameworks.register 'stylesheets', stylesheets

module ThemeImages
	def theme_image(path, mime_type = nil)
		path = path.value
		images_path = File.join(File.dirname(__FILE__), "..", "..", "img" )
		real_path = File.join(images_path, path)
		inline_image_string(data(real_path), compute_mime_type(path, mime_type))
	end
end

module Sass::Script::Functions
  include ThemeImages
end

# Compass configurations
sass_path = dir
css_path = File.join(dir, "..")

# Require any additional compass plugins here.
images_dir = File.join(dir, "..", "..", "images")
output_style = :compressed
environment = :production

