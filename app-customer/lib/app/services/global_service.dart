/*
 * File name: global_service.dart
 * Last modified: 2022.02.26 at 14:50:11
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2022
 */

import 'package:flutter/foundation.dart';
import 'package:get/get.dart';

import '../../common/helper.dart';
import '../models/global_model.dart';

class GlobalService extends GetxService {
  final global = Global().obs;

  Future<GlobalService> init() async {
    try {
      var response = await Helper.getJsonFile('config/global.json');
      global.value = Global.fromJson(response);
    } catch (e) {
      Get.log('GlobalService init fallback: $e');
    }
    return this;
  }

  String get baseUrl {
    if (kIsWeb) {
      return "${Uri.base.origin}/";
    }
    return Helper.toUrl(global.value.laravelBaseUrl ?? '');
  }
}
