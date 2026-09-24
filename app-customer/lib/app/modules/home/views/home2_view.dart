/*
 * File name: home2_view.dart
 * Last modified: 2024.09.24
 * Author: ClubeMkt - https://clubemkt.online
 * Copyright (c) 2024 CitasYa Bolivia
 */

import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../models/slide_model.dart';
import '../../../providers/laravel_provider.dart';
import '../../../routes/app_routes.dart';
import '../../../services/settings_service.dart';
import '../../global_widgets/address_widget.dart';
import '../../global_widgets/home_search_bar_widget.dart';
import '../../global_widgets/notifications_button_widget.dart';
import '../controllers/home_controller.dart';
import '../widgets/categories_carousel_widget.dart';
import '../widgets/featured_categories_widget.dart';
import '../widgets/recommended_carousel_widget.dart';
import '../widgets/slide_item_widget.dart';

class Home2View extends GetView<HomeController> {
  @override
  Widget build(BuildContext context) {
    final double screenWidth = MediaQuery.of(context).size.width;
    final bool isDesktop = screenWidth > 850;

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: () async {
          Get.find<LaravelApiClient>().forceRefresh();
          await controller.refreshHome(showMessage: true);
          Get.find<LaravelApiClient>().unForceRefresh();
        },
        child: CustomScrollView(
          primary: true,
          shrinkWrap: false,
          slivers: <Widget>[
            SliverAppBar(
              backgroundColor: Theme.of(context).scaffoldBackgroundColor,
              elevation: 0.5,
              floating: true,
              pinned: true,
              iconTheme: IconThemeData(color: Theme.of(context).primaryColor),
              title: Text(
                Get.find<SettingsService>().setting.value.appName ?? "CitasYa",
                style: Get.textTheme.titleLarge?.merge(
                  const TextStyle(fontWeight: FontWeight.w800),
                ),
              ),
              centerTitle: true,
              automaticallyImplyLeading: false,
              leading: IconButton(
                icon: const Icon(Icons.sort, color: Color(0xFF0F172A)),
                onPressed: () => Scaffold.of(context).openDrawer(),
              ),
              actions: const [NotificationsButtonWidget()],
              bottom: HomeSearchBarWidget(),
            ),
            SliverToBoxAdapter(
              child: Center(
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 1200),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      // Carousel Slider (Clean unclipped 16:9)
                      Obx(() {
                        if (controller.slider.isEmpty) {
                          return const SizedBox.shrink();
                        }

                        return Container(
                          padding: EdgeInsets.only(
                            top: 8.0,
                            bottom: 12.0,
                            left: isDesktop ? 20.0 : 0.0,
                            right: isDesktop ? 20.0 : 0.0,
                          ),
                          child: Column(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              AspectRatio(
                                aspectRatio: 16 / 9,
                                child: CarouselSlider(
                                  options: CarouselOptions(
                                    autoPlay: true,
                                    autoPlayInterval: const Duration(seconds: 7),
                                    aspectRatio: 16 / 9,
                                    viewportFraction: isDesktop ? 0.95 : 1.0,
                                    enlargeCenterPage: isDesktop,
                                    onPageChanged: (index, reason) {
                                      controller.currentSlide.value = index;
                                    },
                                  ),
                                  items: controller.slider.map((Slide slide) {
                                    return SlideItemWidget(slide: slide);
                                  }).toList(),
                                ),
                              ),
                              const SizedBox(height: 8.0),
                              // Slide Indicators
                              Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: controller.slider.map((Slide slide) {
                                  final int idx = controller.slider.indexOf(slide);
                                  final bool isActive = controller.currentSlide.value == idx;
                                  return AnimatedContainer(
                                    duration: const Duration(milliseconds: 300),
                                    width: isActive ? 22.0 : 7.0,
                                    height: 6.0,
                                    margin: const EdgeInsets.symmetric(horizontal: 3.0),
                                    decoration: BoxDecoration(
                                      borderRadius: BorderRadius.circular(10),
                                      color: isActive
                                          ? const Color(0xFF006948)
                                          : const Color(0xFFCBD5E1),
                                    ),
                                  );
                                }).toList(),
                              ),
                            ],
                          ),
                        );
                      }),
                      AddressWidget(),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                        child: Row(
                          children: [
                            Expanded(
                              child: Text(
                                "Recommended for you".tr,
                                style: Get.textTheme.headlineSmall?.merge(
                                  const TextStyle(fontWeight: FontWeight.w800),
                                ),
                              ),
                            ),
                            MaterialButton(
                              onPressed: () {
                                Get.toNamed(Routes.MAPS);
                              },
                              shape: const StadiumBorder(),
                              color: Get.theme.colorScheme.secondary.withOpacity(0.1),
                              elevation: 0,
                              child: Text("View All".tr, style: Get.textTheme.titleMedium),
                            ),
                          ],
                        ),
                      ),
                      RecommendedCarouselWidget(),
                      Container(
                        color: Get.theme.primaryColor,
                        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 15),
                        child: Row(
                          children: [
                            Expanded(
                              child: Text(
                                "Categories".tr,
                                style: Get.textTheme.headlineSmall?.merge(
                                  const TextStyle(fontWeight: FontWeight.w800),
                                ),
                              ),
                            ),
                            MaterialButton(
                              onPressed: () {
                                Get.toNamed(Routes.CATEGORIES);
                              },
                              shape: const StadiumBorder(),
                              color: Get.theme.colorScheme.secondary.withOpacity(0.1),
                              elevation: 0,
                              child: Text("View All".tr, style: Get.textTheme.titleMedium),
                            ),
                          ],
                        ),
                      ),
                      CategoriesCarouselWidget(),
                      FeaturedCategoriesWidget(),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
