<?php

class ControllerExtensionPaymentPipwave extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('extension/payment/pipwave');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_pipwave', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
        }

        // Text
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        // Configuration fields
        $data['entry_api_key'] = $this->language->get('entry_api_key');
        $data['entry_api_secret'] = $this->language->get('entry_api_secret');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_complete_status'] = $this->language->get('entry_complete_status');
        $data['entry_failed_status'] = $this->language->get('entry_failed_status');
        $data['entry_refund_status'] = $this->language->get('entry_refund_status');
        $data['entry_partial_refund_status'] = $this->language->get('entry_partial_refund_status');
        $data['entry_canceled_status'] = $this->language->get('entry_canceled_status');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['entry_test_mode'] = $this->language->get('entry_test_mode');
        $data['entry_processing_fee_group'] = $this->language->get('entry_processing_fee_group');
        $data['entry_processing_fee_ref'] = $this->language->get('entry_processing_fee_ref');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        // Tooltip
        $data['tooltip_api_key'] = $this->language->get('tooltip_api_key');
        $data['tooltip_api_secret'] = $this->language->get('tooltip_api_secret');
        $data['tooltip_processing_fee_group'] = $this->language->get('tooltip_processing_fee_group');
        // Button text
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['api_key'])) {
            $data['error_api_key'] = $this->error['api_key'];
        } else {
            $data['error_api_key'] = '';
        }

        if (isset($this->error['api_secret'])) {
            $data['error_api_secret'] = $this->error['api_secret'];
        } else {
            $data['error_api_secret'] = '';
        }

        if (isset($this->error['processing_fee_ref'])) {
            $data['error_processing_fee_ref'] = $this->error['processing_fee_ref'];
        } else {
            $data['error_processing_fee_ref'] = '';
        }

        // Breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/pipwave', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/payment/pipwave', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

        if (isset($this->request->post['payment_pipwave_api_key'])) {
            $data['payment_pipwave_api_key'] = $this->request->post['payment_pipwave_api_key'];
        } else {
            $data['payment_pipwave_api_key'] = $this->config->get('payment_pipwave_api_key');
        }

        if (isset($this->request->post['payment_pipwave_api_secret'])) {
            $data['payment_pipwave_api_secret'] = $this->request->post['payment_pipwave_api_secret'];
        } else {
            $data['payment_pipwave_api_secret'] = $this->config->get('payment_pipwave_api_secret');
        }

        if (isset($this->request->post['payment_pipwave_order_status_id'])) {
            $data['payment_pipwave_order_status_id'] = $this->request->post['payment_pipwave_order_status_id'];
        } else {
            $data['payment_pipwave_order_status_id'] = $this->config->get('payment_pipwave_order_status_id');
        }

        if (isset($this->request->post['payment_pipwave_complete_status_id'])) {
            $data['payment_pipwave_complete_status_id'] = $this->request->post['payment_pipwave_complete_status_id'];
        } else {
            $data['payment_pipwave_complete_status_id'] = $this->config->get('payment_pipwave_complete_status_id');
        }

        if (isset($this->request->post['payment_pipwave_failed_status_id'])) {
            $data['payment_pipwave_failed_status_id'] = $this->request->post['payment_pipwave_failed_status_id'];
        } else {
            $data['payment_pipwave_failed_status_id'] = $this->config->get('payment_pipwave_failed_status_id');
        }

        if (isset($this->request->post['payment_pipwave_refund_status_id'])) {
            $data['payment_pipwave_refund_status_id'] = $this->request->post['payment_pipwave_refund_status_id'];
        } else {
            $data['payment_pipwave_refund_status_id'] = $this->config->get('payment_pipwave_refund_status_id');
        }

        if (isset($this->request->post['payment_pipwave_partial_refund_status_id'])) {
            $data['payment_pipwave_partial_refund_status_id'] = $this->request->post['payment_pipwave_partial_refund_status_id'];
        } else {
            $data['payment_pipwave_partial_refund_status_id'] = $this->config->get('payment_pipwave_partial_refund_status_id');
        }

        if (isset($this->request->post['payment_pipwave_canceled_status_id'])) {
            $data['payment_pipwave_canceled_status_id'] = $this->request->post['payment_pipwave_canceled_status_id'];
        } else {
            $data['payment_pipwave_canceled_status_id'] = $this->config->get('payment_pipwave_canceled_status_id');
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        if (isset($this->request->post['payment_pipwave_status'])) {
            $data['payment_pipwave_status'] = $this->request->post['payment_pipwave_status'];
        } else {
            $data['payment_pipwave_status'] = $this->config->get('payment_pipwave_status');
        }

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        if (isset($this->request->post['payment_pipwave_geo_zone_id'])) {
            $data['payment_pipwave_geo_zone_id'] = $this->request->post['payment_pipwave_geo_zone_id'];
        } else {
            $data['payment_pipwave_geo_zone_id'] = $this->config->get('payment_pipwave_geo_zone_id');
        }

        if (isset($this->request->post['payment_pipwave_sort_order'])) {
            $data['payment_pipwave_sort_order'] = $this->request->post['payment_pipwave_sort_order'];
        } else {
            $data['payment_pipwave_sort_order'] = $this->config->get('payment_pipwave_sort_order');
        }

        if (isset($this->request->post['payment_pipwave_test_mode'])) {
            $data['payment_pipwave_test_mode'] = $this->request->post['payment_pipwave_test_mode'];
        } else {
            $data['payment_pipwave_test_mode'] = $this->config->get('payment_pipwave_test_mode');
        }

        $this->load->model('customer/customer_group');
        $data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
        if (isset($this->request->post['payment_pipwave_processing_fee_groups'])) {
            $data['payment_pipwave_processing_fee_groups'] = $this->request->post['payment_pipwave_processing_fee_groups'];
        } else {
            $data['payment_pipwave_processing_fee_groups'] = $this->config->get('payment_pipwave_processing_fee_groups');
        }

        if (isset($this->request->post['payment_pipwave_processing_fee_ref'])) {
            $data['payment_pipwave_processing_fee_ref'] = $this->request->post['payment_pipwave_processing_fee_ref'];
        } else {
            $data['payment_pipwave_processing_fee_ref'] = $this->config->get('payment_pipwave_processing_fee_ref');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['more_info'] = array(
            'step_title' => $this->language->get('step_title'),
            'step_sign_in' => $this->language->get('step_sign_in'),
            'step_sign_up' => $this->language->get('step_sign_up'),
            'step_setup' => $this->language->get('step_setup'),
            'step_configure' => $this->language->get('step_configure'),
            'step_more_info' => $this->language->get('step_more_info'),
        );

        $this->response->setOutput($this->load->view('extension/payment/pipwave', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/pipwave')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['payment_pipwave_api_key']) {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }

        if (!$this->request->post['payment_pipwave_api_secret']) {
            $this->error['api_secret'] = $this->language->get('error_api_secret');
        }

        if (isset($this->request->post['payment_pipwave_processing_fee_groups'])) {
            $pf_groups = $this->request->post['payment_pipwave_processing_fee_groups'];
            if ($pf_groups) {
                foreach ($pf_groups as $i => $value) {
                    if (!isset($this->request->post['payment_pipwave_processing_fee_ref'][$i]) || empty($this->request->post['payment_pipwave_processing_fee_ref'][$i])) {
                        $this->error['processing_fee_ref'][$i] = $this->language->get('error_processing_fee_ref');
                        break;
                    }
                }
            }
        }

        return !$this->error;
    }

}
