/*
 * File name: maps_view.dart
 * Last modified: 2024.09.24
 * Author: CitasYa Bolivia
 */

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart' as gmaps;

import '../controllers/maps_controller.dart';
import '../widgets/maps_carousel_widget.dart';

// ignore: unnecessary_import
import 'dart:ui' as ui;
// ignore: avoid_web_libraries_in_flutter
import 'dart:html' as html;

class MapsView extends GetView<MapsController> {
  @override
  Widget build(BuildContext context) {
    if (kIsWeb) {
      // Register OpenStreetMap interactive embed for Web
      try {
        // ignore: undefined_prefixed_name
        ui.platformViewRegistry.registerViewFactory(
          'openstreetmap-map',
          (int viewId) {
            html.IFrameElement iframeElement = html.IFrameElement();
            iframeElement.src = 'https://www.openstreetmap.org/export/embed.html?bbox=-63.23,-17.83,-63.14,-17.74&layer=mapnik&marker=-17.7833,-63.1821';
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

    return Scaffold(
      body: Stack(
        alignment: AlignmentDirectional.topCenter,
        children: [
          kIsWeb
              ? const HtmlElementView(viewType: 'openstreetmap-map')
              : Obx(() {
                  return gmaps.GoogleMap(
                    mapToolbarEnabled: false,
                    zoomControlsEnabled: false,
                    zoomGesturesEnabled: true,
                    myLocationEnabled: true,
                    padding: const EdgeInsets.only(top: 35),
                    mapType: gmaps.MapType.normal,
                    initialCameraPosition: controller.cameraPosition.value,
                    markers: Set.from(controller.allMarkers),
                    onMapCreated: (gmaps.GoogleMapController _controller) {
                      controller.mapController.value = _controller;
                    },
                    onCameraMoveStarted: () {
                      controller.salons.clear();
                    },
                    onCameraMove: (gmaps.CameraPosition cameraPosition) {
                      controller.cameraPosition.value = cameraPosition;
                    },
                    onCameraIdle: () {
                      controller.getNearSalons();
                    },
                  );
                }),
          Column(
            mainAxisAlignment: MainAxisAlignment.end,
            children: [
              MapsCarouselWidget(),
            ],
          ),
          Container(
            margin: const EdgeInsetsDirectional.only(end: 50, top: 10),
            height: 100,
            child: Row(
              children: [
                IconButton(
                  icon: Icon(Icons.arrow_back_ios, color: Get.theme.hintColor),
                  onPressed: () => Get.back(),
                ),
                Expanded(
                  child: Text(
                    "Maps Explorer".tr,
                    style: Get.textTheme.titleLarge,
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}