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
import 'web_map_helper.dart';

class MapsView extends GetView<MapsController> {
  @override
  Widget build(BuildContext context) {
    if (kIsWeb) {
      registerWebMap();
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