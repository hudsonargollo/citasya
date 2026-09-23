/*
 * File name: main.dart
 * Last modified: 2022.02.17 at 16:26:56
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2022
 */

import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:get/get.dart';
import 'package:get_storage/get_storage.dart';

import 'firebase_options.dart';
import 'app/modules/auth/controllers/auth_controller.dart';
import 'app/modules/home/controllers/home_controller.dart';
import 'app/modules/root/controllers/root_controller.dart';
import 'app/providers/firebase_provider.dart';
import 'app/providers/laravel_provider.dart';
import 'app/routes/theme1_app_pages.dart';
import 'app/services/auth_service.dart';
import 'app/services/firebase_messaging_service.dart';
import 'app/services/global_service.dart';
import 'app/services/settings_service.dart';
import 'app/services/translation_service.dart';

Future<void> initServices() async {
  Get.log('starting services ...');
  await GetStorage.init();
  await Get.putAsync(() => GlobalService().init());
  try {
    await Firebase.initializeApp(
      options: DefaultFirebaseOptions.currentPlatform,
    );
  } catch (e) {
    Get.log('Firebase.initializeApp warning: $e');
  }
  await Get.putAsync(() => AuthService().init());
  await Get.putAsync(() => LaravelApiClient().init());
  await Get.putAsync(() => FirebaseProvider().init());
  await Get.putAsync(() => SettingsService().init());
  await Get.putAsync(() => TranslationService().init());
  Get.lazyPut(() => AuthController(), fenix: true);
  Get.lazyPut(() => RootController(), fenix: true);
  Get.lazyPut(() => HomeController(), fenix: true);
  Get.log('All services started...');
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initServices();

  runApp(
    GetMaterialApp(
      title: Get.find<SettingsService>().setting.value.salonAppName ?? 'Salon App',
      initialRoute: Theme1AppPages.INITIAL,
      onReady: () async {
        if (!kIsWeb) {
          try {
            await Get.putAsync(() => FireBaseMessagingService().init());
          } catch (e) {
            Get.log('FireBaseMessagingService warning: $e');
          }
        }
      },
      getPages: Theme1AppPages.routes,
      localizationsDelegates: GlobalMaterialLocalizations.delegates,
      supportedLocales: Get.find<TranslationService>().supportedLocales(),
      translationsKeys: Get.find<TranslationService>().translations,
      locale: Get.find<TranslationService>().getLocale(),
      fallbackLocale: Get.find<TranslationService>().getLocale(),
      debugShowCheckedModeBanner: false,
      defaultTransition: Transition.cupertino,
      themeMode: Get.find<SettingsService>().getThemeMode(),
      theme: Get.find<SettingsService>().getLightTheme(),
      darkTheme: Get.find<SettingsService>().getDarkTheme(),
    ),
  );
}
