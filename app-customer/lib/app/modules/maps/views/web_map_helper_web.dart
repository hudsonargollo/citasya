// ignore: avoid_web_libraries_in_flutter
import 'dart:html' as html;
// ignore: unnecessary_import
import 'dart:ui' as ui;

void registerWebMapImpl() {
  try {
    // ignore: undefined_prefixed_name
    ui.platformViewRegistry.registerViewFactory(
      'openstreetmap-map',
      (int viewId) {
        html.IFrameElement iframeElement = html.IFrameElement();
        iframeElement.src =
            'https://www.openstreetmap.org/export/embed.html?bbox=-63.23,-17.83,-63.14,-17.74&layer=mapnik&marker=-17.7833,-63.1821';
        iframeElement.style.border = 'none';
        iframeElement.style.width = '100%';
        iframeElement.style.height = '100%';
        return iframeElement;
      },
    );
  } catch (e) {
    // already registered
  }
}
