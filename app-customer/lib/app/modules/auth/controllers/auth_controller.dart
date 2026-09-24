import 'dart:async';

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/user_model.dart';
import '../../../repositories/user_repository.dart';
import '../../../routes/app_routes.dart';
import '../../../services/auth_service.dart';
import '../../../services/firebase_messaging_service.dart';
import '../../../services/settings_service.dart';
import '../../root/controllers/root_controller.dart';

class AuthController extends GetxController {
  final Rx<User> currentUser = Get.find<AuthService>().user;
  GlobalKey<FormState> loginFormKey = GlobalKey<FormState>();
  GlobalKey<FormState> registerFormKey = GlobalKey<FormState>();
  GlobalKey<FormState> forgotPasswordFormKey = GlobalKey<FormState>();
  final hidePassword = true.obs;
  final loading = false.obs;
  final smsSent = ''.obs;
  late UserRepository _userRepository;

  AuthController() {
    loginFormKey = GlobalKey<FormState>();
    registerFormKey = GlobalKey<FormState>();
    forgotPasswordFormKey = GlobalKey<FormState>();
    _userRepository = UserRepository();
  }

  void login() async {
    Get.focusScope?.unfocus();
    if (loginFormKey.currentState != null && loginFormKey.currentState!.validate()) {
      loginFormKey.currentState!.save();
      loading.value = true;
      try {
        try {
          await Get.find<FireBaseMessagingService>().setDeviceToken();
        } catch (e) {
          Get.log('FCM token notice: $e');
        }

        currentUser.value = await _userRepository.login(currentUser.value);

        try {
          if (currentUser.value.email != null && currentUser.value.apiToken != null) {
            await _userRepository.signInWithEmailAndPassword(
              currentUser.value.email!,
              currentUser.value.apiToken!,
            );
          }
        } catch (e) {
          Get.log('Firebase signin notice: $e');
        }

        try {
          await Get.find<RootController>().changePage(0);
        } catch (_) {
          Get.offAllNamed(Routes.ROOT);
        }
      } catch (e) {
        Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
      } finally {
        loading.value = false;
      }
    }
  }

  void register() async {
    Get.focusScope?.unfocus();
    if (registerFormKey.currentState != null && registerFormKey.currentState!.validate()) {
      registerFormKey.currentState!.save();
      loading.value = true;
      try {
        if (Get.find<SettingsService>().setting.value.enableOtp ?? false) {
          await _userRepository.sendCodeToPhone();
          loading.value = false;
          await Get.toNamed(Routes.PHONE_VERIFICATION);
        } else {
          try {
            await Get.find<FireBaseMessagingService>().setDeviceToken();
          } catch (e) {
            Get.log('FCM token notice: $e');
          }

          currentUser.value = await _userRepository.register(currentUser.value);

          try {
            if (currentUser.value.email != null && currentUser.value.apiToken != null) {
              await _userRepository.signUpWithEmailAndPassword(
                currentUser.value.email!,
                currentUser.value.apiToken!,
              );
            }
          } catch (e) {
            Get.log('Firebase signup notice: $e');
          }

          try {
            await Get.find<RootController>().changePage(0);
          } catch (_) {
            Get.offAllNamed(Routes.ROOT);
          }
        }
      } catch (e) {
        Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
      } finally {
        loading.value = false;
      }
    }
  }

  Future<void> verifyPhone() async {
    try {
      loading.value = true;
      await _userRepository.verifyPhone(smsSent.value);
      try {
        await Get.find<FireBaseMessagingService>().setDeviceToken();
      } catch (e) {
        Get.log('FCM token notice: $e');
      }

      currentUser.value = await _userRepository.register(currentUser.value);

      try {
        if (currentUser.value.email != null && currentUser.value.apiToken != null) {
          await _userRepository.signUpWithEmailAndPassword(
            currentUser.value.email!,
            currentUser.value.apiToken!,
          );
        }
      } catch (e) {
        Get.log('Firebase signup notice: $e');
      }

      try {
        await Get.find<RootController>().changePage(0);
      } catch (_) {
        Get.offAllNamed(Routes.ROOT);
      }
    } catch (e) {
      Get.back();
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    } finally {
      loading.value = false;
    }
  }

  Future<void> resendOTPCode() async {
    await _userRepository.sendCodeToPhone();
  }

  void sendResetLink() async {
    Get.focusScope?.unfocus();
    if (forgotPasswordFormKey.currentState != null && forgotPasswordFormKey.currentState!.validate()) {
      forgotPasswordFormKey.currentState!.save();
      loading.value = true;
      try {
        await _userRepository.sendResetLinkEmail(currentUser.value);
        loading.value = false;
        Get.showSnackbar(Ui.SuccessSnackBar(
          message: "The Password reset link has been sent to your email: ".tr + (currentUser.value.email ?? ''),
        ));
        Timer(Duration(seconds: 4), () {
          Get.offAndToNamed(Routes.LOGIN);
        });
      } catch (e) {
        Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
      } finally {
        loading.value = false;
      }
    }
  }
}
