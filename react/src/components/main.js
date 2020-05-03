import React from 'react'
import Router from './router'
import {connect} from 'react-redux'
import {getCurrntUser, getConfig, setContainer} from '../actions/main';
import {getLanguage} from '../actions/language';

@connect((store) => {
    return {
        currentUser: store.main.currentUser,
        header: store.main.header,
        config: store.main.config,
        language: store.language.data
    }
})

export default class Main extends React.Component {

    constructor() {
        super()
        this.container
    }

    componentDidMount() {
        const {dispatch} = this.props
        // NOTE: fetch needed data
        dispatch(getCurrntUser())
        dispatch(getLanguage())
        dispatch(getConfig())
        // dispatch(setContainer(this.container))
    }

    render() {
        const {currentUser, language, config, dispatch} = this.props
        return currentUser === null || language === null || config === null ? null :
            <div class='padding-20'>
                <div class='panel panel-default'>
                    <div class='panel-heading panel-heading-transparent'>
                        <div class='header'>
                            <strong>{this.props.header}</strong>
                        </div>
                    </div>
                    <div ref={ref => dispatch(setContainer(ref))} class='panel-body'>
                        <Router/>
                    </div>
                </div>
            </div>

    }
}