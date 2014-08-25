require 'sinatra'

module TemplateUtils
  ##
  # Overrides find_template to use different directories for different engines
  #
  def find_template(views, name, engine, &block)
    _, folder = views.detect { |k,v| engine == Tilt[k] }
    folder ||= views[:default]
    super(folder, name, engine, &block)
  end

  ##
  # Determines the last modified date of the file corresponding to the specified template.
  #
  def last_modified_date(name, engine)
    find_template settings.views, name, engine do |file|
      return File.mtime(file) if File.exists?(file)
    end
    Time.now
  end

  ##
  # Determines the last modified date of the specified page.
  #
  def page_modified(name)
    [
      last_modified_date(name, Tilt[:markdown]),
      last_modified_date(:main_layout, Tilt[:erb]),
      last_modified_date(:footer, Tilt[:erb])
    ].max
  end

  ##
  # Determines the last modified date of the specified style.
  def style_modified(name)
    last_modified_date(name, Tilt[:sass])
  end

  def h(text)
    Rack::Utils.escape_html(text)
  end
end