import React from 'react'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'

export default class Test extends React.Component {
    render() {
        const test = (
            <Row gutter={8}>
                <Col span={24} sm={6}>
                    <Popover
                        placement='right'
                        trigger='click'
                        visible={visible}
                        content={
                            <Row>
                                <Input
                                    value={year}
                                    style={{ width: 60 }}
                                    placeholder='Year'
                                    maxLength={4}
                                    onChange={ev =>
                                        this.changeYear(ev.target.value)
                                    }
                                />
                                <Tag
                                    class='clickable tag-medium tag-success'
                                    onClick={() => this.submitYear()}
                                >
                                    {loading ? (
                                        <Icon type='loading' />
                                    ) : (
                                        lang.calendarList.add
                                    )}
                                </Tag>
                            </Row>
                        }
                    >
                        <Card
                            class='clickable'
                            onClick={() => this.visible(!visible)}
                        >
                            <Icon type='plus' />
                            <span style={{ marginLeft: 8 }}>ADD</span>
                        </Card>
                    </Popover>
                </Col>
                {calendars === null
                    ? null
                    : calendars.map(calendar => (
                          <Col
                              key={calendar.calendar_id}
                              span={24}
                              sm={6}
                              onClick={() => {
                                  window.location = `${URL.EGS_BASE}/#/calendar/${calendar.calendar_id}`
                              }}
                          >
                              <Card
                                  class={`clickable ${
                                      !calendar.calendar_active
                                          ? ''
                                          : 'background-success'
                                  }`}
                              >
                                  {calendar.calendar_id}
                              </Card>
                          </Col>
                      ))}
            </Row>
        )

        return false
    }
}
