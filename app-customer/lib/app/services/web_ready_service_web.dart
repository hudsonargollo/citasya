// ignore: avoid_web_libraries_in_flutter
import 'dart:html' as html;

void notifyAppFullyLoaded() {
  try {
    html.window.dispatchEvent(html.CustomEvent('citasya-app-fully-loaded'));
  } catch (e) {
    // ignore
  }
}
